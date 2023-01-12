<script type="text/javascript">

    // La Librería JS, jQuery (jquery-1.10.2.min.js), CSS y otros se carga en la cabecera del sistema

    function GetTipoReporte() {
        return $("[name=tipoReporte]:checked").val();
    }

    function Ajax_GenerarReporte(dato_vista) {
        var perfil_app = $("input:radio[name=perfil_app]:checked").val();
        // var opcionResultado = $("input:radio[name=opcionResultado]:checked").val();
        var opcionResultado = "tabla";
        var filtros = [];
        $("#tbParametrosReporte>tbody>tr").each(function () {filtros.push(JSON.parse($(this).attr("datos")));});
        var parametros = {"opcionResultado":opcionResultado, "perfil_app":perfil_app, "vista":dato_vista,"filtros":filtros,"tipo":GetTipoReporte()};
        var strParametros = "&parametros=" + encodeURIComponent(JSON.stringify(parametros));
        Ajax_CargadoGeneralPagina('Consultas/Generar', 'divResultadoReporte', "divErrorBusqueda", 'SLASH', strParametros);
    }

    function AgregarFiltro() {
        var tipo = GetTipoReporte();
        var filtro = 'Consultas/AgregarFiltro/Prospecto';
        
        switch (tipo) {
            case 'mantenimiento':
            
                filtro = 'Consultas/AgregarFiltro/Mantenimiento';
            
                break;

            case 'solcred':
            
                filtro = 'Consultas/AgregarFiltro/SolCred';
            
                break;

            default:

                break;
        }
        
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
        
        $("tr.OpcionConsultaPerfil").addClass("ui-helper-hidden");
        $("tr.OpcionConsultaTipo").addClass("ui-helper-hidden");
        
        if(tipo == 'prospecto')
        {
            $("#sol_titulo_monto_aux").fadeIn();
        }
        else
        {
            $("#sol_titulo_monto_aux").fadeOut();
        }
        
        switch (tipo) {
            case "mantenimiento":

                $("tr.OpcionConsultaPerfil").removeClass("ui-helper-hidden");

                break;

            default:
        
                $("tr.OpcionConsultaTipo").removeClass("ui-helper-hidden");
        
                break;
        }
        
        
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"><?php echo $this->lang->line('ConsultaTitulo') . $nombre_region; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('ConsultaSubtitulo'); ?></div>


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
                            
                            <input id="tipoReporteSeguimiento" name="tipoReporte" type="radio" value="prospecto" CHECKED onchange="ActualizaTipoConsulta();" />
                            <label for="tipoReporteSeguimiento" class="changeReporte"><span></span><?php echo $this->lang->line('consulta_prospectos'); ?></label>

                        </div>
                        
                        <div style="float: left;">
                            
                            <input id="tipoReporteSolcred" name="tipoReporte" type="radio" value="solcred" onchange="ActualizaTipoConsulta();" />
                            <label for="tipoReporteSolcred" class="changeReporte"><span></span><?php echo $this->lang->line('consulta_solcred'); ?></label>
                            
                        </div>
                        
                        <div style="float: left;">
                            
                            <input id="tipoReporteDocumentos" name="tipoReporte" type="radio" value="mantenimiento" onchange="ActualizaTipoConsulta();" />
                            <label for="tipoReporteDocumentos" class="changeReporte"><span></span><?php echo $this->lang->line('consulta_mantenimientos'); ?></label>
                            
                        </div>
                        
                    </td>
                </tr>
                
                <?php
                /*
                echo '
                
                    <tr class="FilaGris OpcionConsultaTipo">
                        <td style="width:20%">
                            <strong> Seleccione tipo resultado </strong>
                        </td>
                        <td style="width:80%">

                            <input id="opcionTabla" name="opcionResultado" type="radio" class="" checked="checked" value="tabla" />
                            <label for="opcionTabla" class=""><span></span><strong> Tabla </strong></label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="opcionPivot" name="opcionResultado" type="radio" class="" value="pivot" />
                            <label for="opcionPivot" class=""><span></span><strong> Constructor Reporte Pivot </strong></label>

                        </td>
                    </tr>
                
                    ';
                */
                ?>
                
                <tr class="FilaGris OpcionConsultaPerfil ui-helper-hidden">
                    <td style="width:20%">
                        <strong> Seleccione el <?php echo $this->lang->line('consulta_perfil_app'); ?> </strong>
                    </td>
                    <td style="width:80%">
                        
                        <?php
                    
                            if(isset($arrPerfilApp[0]))
                            {
                                foreach ($arrPerfilApp as $key => $value) 
                                {
                                ?>
                                    <div class="divOpciones">
                                        <input id="perfil_app<?php echo $value["perfil_app_id"]; ?>" name="perfil_app" type="radio" class="" <?php if(1 == $value["perfil_app_id"]){ echo "checked='checked'"; } ?> value="<?php echo $value["perfil_app_id"]; ?>" />
                                        <label for="perfil_app<?php echo $value["perfil_app_id"]; ?>" class=""><span></span><strong><?php echo $value["perfil_app_nombre"]; ?></strong></label>
                                    </div>
                                <?php
                                }
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
