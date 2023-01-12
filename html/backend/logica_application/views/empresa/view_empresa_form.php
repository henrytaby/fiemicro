<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Empresa/Guardar',
            'divVistaMenuPantalla', 'divErrorListaResultado');

    $("#divCargarFormulario").show();    
    $("#confirmacion").hide();

    function MostrarConfirmación()
    {
        $("#divCargarFormulario").hide();
        $("#confirmacion").fadeIn(500);
    }
    
    function OcultarConfirmación()
    {
        $("#divCargarFormulario").fadeIn(500);    
        $("#confirmacion").hide();
    }
    
    function Ajax_CargarAccion_VerificarNIT() {
        var codigo = $('#solicitud_nit').val();
        if(codigo == '')
        {
            alert(' :: Ingrese un NIT válido ::');
        }
        else
        {
            var strParametros = "&codigo=" + codigo;
            Ajax_CargadoGeneralPagina('Empresa/Verificar/NIT', 'resultadoNIT', "divErrorListaResultado", '', strParametros);
        }
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('EmpresaTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('EmpresaSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <br />
        
        <form id="FormularioRegistroLista" method="post">
            
        <?php // *** PASO 1: CRITERIOS DEL PROSPECTO COMO EL NIT, CATEGORÍA DE COMERCIO Y EL TIPO DE PERSONA *** ?>

        <div class="FormularioSubtituloMediano" style="margin-right: 5%;"> <?php echo $this->lang->line('empresa_paso1'); ?></div>

        <div style="clear: both"></div>

        <br />

        <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

            <?php $strClase = "FilaGris"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_nit'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='<?php echo $this->lang->line('Ayuda_mantenimiento_nit'); ?>' data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">
                    
                    <table style="width: 100%;" border="0">
                        <tr>
                            <td style="width: 30%; text-align: center;">
                                
                                <span class="EnlaceSimple" onclick="Ajax_CargarAccion_VerificarNIT()">
                                    <strong><i class="fa fa-search" aria-hidden="true"></i> <?php echo $this->lang->line('solicitud_verificar_nit'); ?></strong>
                                </span>
                                
                            </td>
                            <td style="width: 70%;">
                                <?php echo $arrCajasHTML["solicitud_nit"]; ?>
                            </td>
                        </tr>
                    </table>                    
                    
                </td>

            </tr>

        </table>

        <br />
        
        <div id="resultadoNIT"></div>
        
        <div id="divErrorListaResultadoFlotante" class="mensajeBD"> </div>
        
        <br /><br />
        
        <?php // *** PASO 2: ASIGNACIÓN DE FECHA Y HORA DE LA VISITA *** ?>

        <div class="FormularioSubtituloMediano" style="margin-right: 5%;"> <?php echo $this->lang->line('empresa_paso2'); ?></div>

        <div style="clear: both"></div>

        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

            <?php $strClase = "FilaGris"; ?>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                <td style="width: 30%; font-weight: bold;">

                    <?php echo $this->lang->line('empresa_ejecutivo'); ?>

                </td>
                <td style="width: 70%;">
                    <?php

                        if(isset($arrEjecutivo[0]))
                        {
                            echo html_select('codigo_ejecutivo', $arrEjecutivo, 'usuario_id', 'usuario_nombre', '', '');
                        }

                    ?>
                </td>
            </tr>

        </table>        
        
        </form>
            
        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Menu/Principal');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('aprobar_empresa_Pregunta'); ?></div>
        
            <div style="clear: both"></div>
        
            <br />

        <div class="PreguntaConfirmar">
            <?php echo $this->lang->line('PreguntaContinuar'); ?>
        </div>

        <div class="Botones2Opciones">
            <a onclick="OcultarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a id="btnGuardarDatosLista" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
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
</div>