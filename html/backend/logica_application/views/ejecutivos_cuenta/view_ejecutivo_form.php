<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Ejecutivo/Guardar',
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

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

        <?php
        
            $nombre_bandeja = $this->mfunciones_generales->GetValorCatalogo($_SESSION["identificador_tipo_perfil_app"], 'tipo_perfil_app_singular');
        
            if($tipo_accion == 0)
            {
                $tituloF = $this->lang->line('EjecutivoTitulo_nuevo');
                $subtituloF = sprintf($this->lang->line('EjecutivoSubtitulo_nuevo'), $nombre_bandeja);
                $pregunta = sprintf($this->lang->line('ejecutivo_Pregunta1'), $nombre_bandeja);
            }
            
            if($tipo_accion == 1)
            {
                $tituloF = $this->lang->line('EjecutivoTitulo_editar');
                $subtituloF = $this->lang->line('EjecutivoSubtitulo_editar');
                $pregunta = $this->lang->line('ejecutivo_Pregunta2');
            }
        
        ?>
        
            <div class="FormularioSubtitulo"> <?php echo $tituloF . $nombre_bandeja . $nombre_region; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $subtituloF; ?></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["ejecutivo_id"])){ echo $arrRespuesta[0]["ejecutivo_id"]; } ?>" />

            <input type="hidden" name="tipo_accion" value="<?php echo $tipo_accion; ?>" />
            
            <?php
            
            if($tipo_accion == 1)
            {
                echo "<div class='mensaje_advertencia'> " . $this->lang->line('ejecutivo_advertencia') . " </div> <br />";
            }
            
            ?>
            
        <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php
            if($tipo_accion == 1)
            {
            ?>
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        Usuario App Actual
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrRespuesta[0]["usuario_nombre"]; ?>
                    </td>

                </tr>
            <?php
            }
            ?>
                
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('ejecutivo_nombre'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php
                        echo $arrParent;
                    ?>
                </td>

            </tr>
        </table>
        
        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Ejecutivo/Ver/<?php echo $_SESSION["identificador_tipo_perfil_app"]; ?>');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
        

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $pregunta ?></div>
        
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