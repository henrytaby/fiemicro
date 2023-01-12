<script type="text/javascript">

    // La Librería JS, jQuery (jquery-1.10.2.min.js), CSS y otros se carga en la cabecera del sistema

    function GetTipoReporte() {
        return $("[name=tipoReporte]:checked").val();
    }

    function Ajax_GenerarReporte(dato_vista) {
        var filtros = [];
        $("#tbParametrosReporte>tbody>tr").each(function () {filtros.push(JSON.parse($(this).attr("datos")));});
        var parametros = {"vista":dato_vista,"filtros":filtros,"tipo":GetTipoReporte()};
        var strParametros = "&parametros=" + encodeURIComponent(JSON.stringify(parametros));
        Ajax_CargadoGeneralPagina('Afiliador/Consultas/Generar', 'divResultadoReporte', "divErrorBusqueda", 'SLASH', strParametros);
    }

    function AgregarFiltro() {
        var tipo = GetTipoReporte();
        var filtro = 'Afiliador/AgregarFiltro/Registros';
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
    
    $('.changeReporte').click(function(){        
        if($("#tbParametrosReporte tbody").children().length > 0)
        {
            var cnfrm = confirm('<?php echo $this->lang->line('consulta_pregunta_reporte'); ?>');
            if(cnfrm != true)
            {
                return false;
            }
            else
            {
                var row = document.getElementById('tbParametrosReporte').getElementsByTagName('tbody')[0];        
                row.parentNode.removeChild(row);
            }
        }
    });
    
    function ActualizaTipoConsulta() {
        var tipo = GetTipoReporte();
        if (tipo=="mantenimiento") {
            $("tr.OpcionConsultaPerfil").removeClass("ui-helper-hidden");
        } else {
            $("tr.OpcionConsultaPerfil").addClass("ui-helper-hidden");
        }
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"><?php echo $this->lang->line('ConsultaTerceroTitulo') . $nombre_region; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('ConsultaTerceroSubtitulo'); ?></div>


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
                            
                            <input id="tipoReporteRegistro" name="tipoReporte" type="radio" value="registros" CHECKED onchange="ActualizaTipoConsulta();" />
                            <label for="tipoReporteRegistro" class="changeReporte"><span></span><?php echo $this->lang->line('consulta_terceros_registros'); ?></label>
                            
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            
                        </div>
                        
                        <div style="float: left;">
                            
                            <input id="tipoReportePivot" name="tipoReporte" type="radio" value="pivot" onchange="ActualizaTipoConsulta();" />
                            <label for="tipoReportePivot" class="changeReporte"><span></span><?php echo $this->lang->line('consulta_terceros_piviot'); ?></label>
                            
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            
                        </div>
                        
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
                        <a id="btnAgregarFiltro" class="BotonSimple" style="display:inline-block; float:right; max-width:175px!important;" onclick="AgregarFiltro();"> <?php echo $this->lang->line('reportes_boton_agregar_filtro'); ?> </a>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <br /><br />

        <div class="Botones2Opciones">
            
            <a id="btnGenerarTabla" class="BotonMinimalista" onclick="Ajax_GenerarReporte('tabla');"> <?php echo $this->lang->line('reportes_generar_tabla'); ?> </a>
            
        </div>

        <div style="clear: both"></div>
        <?php // Si hay un error PHP o DB se mostra´ra en este DIV ?>
        <div id="divErrorBusqueda" class="mensajeBD">  </div>

        <div id="divResultadoReporte"></div>
        
    </div>
</div>
