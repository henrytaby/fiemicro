<script type="text/javascript">

    // La Librería JS, jQuery (jquery-1.10.2.min.js), CSS y otros se carga en la cabecera del sistema

    function Ajax_GenerarReporte(dato_vista) {
        var filtros = [];
        $("#tbParametrosReporte>tbody>tr").each(function () {filtros.push(JSON.parse($(this).attr("datos")));});
        var parametros = {"vista":dato_vista,"filtros":filtros};
        var strParametros = "&parametros=" + encodeURIComponent(JSON.stringify(parametros));
        Ajax_CargadoGeneralPagina('Agencia/Consultas/Generar', 'divResultadoReporte', "divErrorBusqueda", 'SLASH', strParametros);
    }

    function AgregarFiltro() {
        var filtro = 'Agencia/AgregarFiltro/Registros';
        Ajax_CargadoGeneralPagina(filtro, 'divElementoFlotante', "divErrorBusqueda", '', "");
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
    
    function VolverBandeja(bandeja) {
        if($("#tbParametrosReporte tbody").children().length > 0)
        {
            var cnfrm = confirm('<?php echo $this->lang->line('agencia_pregunta_reporte'); ?>');
            if(cnfrm != true)
            {
                return false;
            }
            else
            {
                Ajax_CargarOpcionMenu(bandeja);
            }
        }
        else
        {
            Ajax_CargarOpcionMenu(bandeja);
        }
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"><?php echo $this->lang->line('AgenciaTercerosTitulo') . ' - ' . $this->lang->line('bandeja_agencia_atendidas') . $nombre_region; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('AgenciaTercerosReporteSubtitulo'); ?></div>

        <div style="clear: both"></div>
        <br />
        <div align="left" class="BotonesVariasOpciones">
            
            <span class="BotonMinimalista" onclick="VolverBandeja('Afiliador/Agencia/Ver');">
                <?php echo $this->lang->line('bandeja_agencia_pendientes'); ?> 
            </span>
        </div>
        
        <div align="left" class="BotonesVariasOpciones">
            
            <span class="BotonMinimalista" onclick="VolverBandeja('Afiliador/Agencia/Reporte');">
                <?php echo $this->lang->line('bandeja_agencia_atendidas'); ?> 
            </span>
        </div>

        <div style="clear: both;"> </div>

        <div id="divParametrosReporte">
            <table id="tbParametrosReporte" class="tablaresultados TextoMayuscula" style="width: 90% !important;">
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3">
                        <a id="btnAgregarFiltro" class="BotonSimple" style="display:inline-block; float:right; max-width:175px!important;" onclick="AgregarFiltro();"> <?php echo $this->lang->line('reportes_boton_agregar_filtro'); ?> </a>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <br /><br />

        <div class="Botones2Opciones" style="float: right;">
            
            <a id="btnGenerarTabla" class="BotonMinimalista" onclick="Ajax_GenerarReporte('tabla');"> <?php echo $this->lang->line('reportes_generar_tabla'); ?> </a>
            
        </div>

        <div style="clear: both"></div>
        <?php // Si hay un error PHP o DB se mostra´ra en este DIV ?>
        <div id="divErrorBusqueda" class="mensajeBD">  </div>

        <div id="divResultadoReporte"></div>
        
    </div>
</div>
