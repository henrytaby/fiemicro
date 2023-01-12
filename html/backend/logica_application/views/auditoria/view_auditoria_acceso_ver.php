<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Auditoria/Acceso/Resultado',
            'divResultadoReporte', 'divErrorListaResultado', 'SLASH');

    function Volver(){
        $('html,body').animate({scrollTop: $('#divCargarFormulario').show().offset().top - 80}, 600);
    }

	$(document).ready(function(){
        $("#fecha_inicio").datepicker({changeYear: true, changeMonth: true, onSelect: function(selected) {
              $("#fecha_fin").datepicker("option","minDate", selected)}});
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

            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('AuditoriaAccesoTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('AuditoriaAccesoSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <br />
        
            <form id="FormularioRegistroLista" method="post">

                <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                    <?php $strClase = "FilaGris"; ?>

                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%;">
                                <strong> <?php echo $this->lang->line('auditoria_usuario_detectado'); ?> </strong>
                        </td>

                        <td style="width: 70%;">

                            <?php

                            if(count($arrListadoAcceso[0]) > 0)
                            {
                                echo html_select('acceso', $arrListadoAcceso, 'lista_codigo', 'lista_valor', '', '');
                            }
                            else
                            {
                                echo $this->lang->line('TablaNoRegistros');
                            }
                            ?>

                        </td>

                    </tr>

                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%;">
                                <strong> <?php echo $this->lang->line('auditoria_tipo_acceso'); ?> </strong>
                        </td>

                        <td style="width: 70%;">

                            <?php

                            if(count($arrListadoAccion[0]) > 0)
                            {
                                echo html_select('accion', $arrListadoAccion, 'lista_codigo', 'lista_valor', '', '');
                            }
                            else
                            {
                                echo $this->lang->line('TablaNoRegistros');
                            }
                            ?>

                        </td>

                    </tr>

                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%;">
                                <span> <strong> <?php echo $this->lang->line('auditoria_fechas'); ?> </strong> </span>
                                <a onclick="LimpiarFechas();" title="Limpiar Fechas" >
                                        <img src="html_public/imagenes/limpiar_pequeno.png" style="vertical-align: bottom; height: 20px;" />
                                </a>
                        </td>

                        <td style="width: 70%;">                

                            <table style="width: 100%; border: 0px;">
                                <tr>
                                        <td style="width: 50%;" valign="bottom">
                                                <strong> DEL: <?php echo $arrCajasHTML["fecha_inicio"]; ?> </strong>
                                        </td>
                                        <td style="width: 50%;" valign="bottom">
                                                <strong> AL: <?php echo $arrCajasHTML["fecha_fin"]; ?> </strong>
                                        </td>
                                </tr>
                            </table>
                        </td>

                    </tr>

                </table>

            </form>
	
        <br />
        
        <div class="Botones2Opciones" style="float: right;">
            <a id="btnGuardarDatosLista" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
		
        <div style="clear: both"></div>
        
		<div id="divErrorListaResultado" class="mensajeBD"> </div>
		
        <div id="divResultadoReporte" style="width: 100%;"> </div>
        
        <br /><br /><br /><br /><br /><br />

    </div>
    
</div>