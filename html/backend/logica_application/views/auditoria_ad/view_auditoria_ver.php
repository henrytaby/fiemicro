<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'AuditoriaAD/Resultado','divResultadoReporte', 'divErrorListaResultado', 'SLASH');

    $(document).ready(function(){
        $("#fecha_inicio").datepicker({changeYear: true, changeMonth: true, onSelect: function(selected) {
              $("#fecha_fin").datepicker("option","minDate", selected);
        }});
        $("#fecha_fin").datepicker({changeYear: true, changeMonth: true});
    });

	function LimpiarFechas() {
        $("#fecha_inicio").val('');
        $("#fecha_fin").val('');
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('title_report_ad') ?> </div>
            <div class="FormularioSubtituloComentarioNormal ">
            <?php echo $this->lang->line('head_subtitle_comment') ?></div>

        <div style="clear: both"></div>

        <br />

		<form id="FormularioRegistroLista" method="post">

                    <input type="hidden" id="tipo" name="tipo" value="tabla" />
                    <input type="hidden" id="accion_gestion" name="accion_gestion" value="<?php echo $accion_gestion; ?>" />

                    <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

                        <?php $strClase = "FilaGris";?>
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 15%; text-align: right;">
                                <span> <strong> <?php echo $this->lang->line('date_range_ad') ?> </strong> </span>
                                <a onclick="LimpiarFechas();" title="Limpiar Fechas" >
                                    <img src="html_public/imagenes/limpiar_pequeno.png" style="vertical-align: bottom; height: 20px;" />
                                </a>
                            </td>

                            <td style="width: 85%;" <?php echo ($accion_gestion == 'seguimiento' ? 'colspan="2"' : ''); ?>>

                                <table style="width: 100%; border: 0px;">
                                    <tr>
                                        <td style="width: 50%;" valign="bottom">
                                            <strong> Del: <?php echo $arrCajasHTML["fecha_inicio"]; ?> </strong>
                                        </td>
                                        <td style="width: 50%;" valign="bottom">
                                                <strong> Al: <?php echo $arrCajasHTML["fecha_fin"]; ?> </strong>
                                        </td>
                                    </tr>
                                </table>

                            </td>

                        </tr>

                    </table>

		</form>

	<br />
        <div>
            <a id="btnGuardarDatosLista" class="BotonMinimalista"> <i class="fa fa-search" aria-hidden="true"></i> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>

        <div style="clear: both"></div>

        <div id="divErrorListaResultado" class="mensajeBD"> </div>

        <div id="divResultadoReporte" style="width: 100%;"> </div>

        <br /><br /><br /><br /><br /><br />

    </div>
</div>