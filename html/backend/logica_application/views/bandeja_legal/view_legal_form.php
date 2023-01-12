<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Bandeja/Legal/Guardar',
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

    function Ajax_CargarAccion_DetalleUsuario(tipo_codigo, codigo_usuario) {
        var strParametros = "&tipo_codigo=" + tipo_codigo + "&codigo_usuario=" + codigo_usuario;
        Ajax_CargadoGeneralPagina('Usuario/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_EvalPdf(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Legal/Evaluacion/PDF', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function ExportarPDF(tipo) {
        var w = window.open('about:blank');
        w.document.write('<form method="post" id="formID" action="Evaluacion/Reporte">Generando Reporte...<input type="hidden" value="' + tipo + '" name="codigo"/></form>');
        w.document.getElementById("formID").submit();
        return false;
    }
    

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('LegalTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('LegalSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["prospecto_id"])){ echo $arrRespuesta[0]["prospecto_id"]; } ?>" />

        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaGris"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('prospecto_id'); ?>
                </td>

                <td style="width: 70%;">
                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleProspecto('<?php echo $arrRespuesta[0]["prospecto_id"]; ?>')">
                        <?php echo PREFIJO_PROSPECTO . $arrRespuesta[0]["prospecto_id"]; ?>                                    
                    </span>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('prospecto_nombre_empresa'); ?> Empresa
                </td>

                <td style="width: 70%;">
                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleEmpresa('<?php echo $arrRespuesta[0]["empresa_id"]; ?>')">
                        <?php echo $arrRespuesta[0]["empresa_nombre"]; ?>
                    </span>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_categoria_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrRespuesta[0]["empresa_categoria"]; ?>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('ejecutivo_asignado_nombre'); ?>
                </td>

                <td style="width: 70%;">
                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleUsuario(0, '<?php echo $arrRespuesta[0]["usuario_id"]; ?>')">
                        <?php echo $arrRespuesta[0]["ejecutivo_asignado_nombre"]; ?>
                    </span>                    
                </td>
            </tr>
            
        </table>
        
        <br />
        
        <?php
        // Se pregunta si la empresa es un Comercio y tiene registrado su evaluación
        // Si es así, es mandatorio que lo registre
        if($arrRespuesta[0]["empresa_categoria_codigo"] == 1 && $evaluacion_legal_reporte == FALSE)
        {
             echo "<div class='mensaje_alerta' style='text-align: center !important;'> " . $this->lang->line('legal_advertencia') . " Puede hacerlo desde <span class='EnlaceSimple' style='color: #ffffff !important;' onclick='Ajax_CargarAccion_Evaluacion(\"" . $arrRespuesta[0]['prospecto_id'] . "\");'> <u>Este Enlace</u></span> </div> <br />";
        }
        else
        {
        ?>
        
            <table class="tblListas Centrado" style="width: 80%;" border="0">

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="FilaCabecera">
                    <th colspan="3" style="width:80%;">
                        <strong><?php echo $this->lang->line('legal_evaluacion_opcion'); ?></strong>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="FilaBlanca">
                    <td style="text-align: center; width: 40%">
                        <strong><?php echo $this->lang->line('legal_evaluacion'); ?></strong>
                    </td>

                    <td style="text-align: center; width: 20%">
                        
                        
                        
                    </td>

                    <td style="text-align: center; width: 20%">
                        
                        <span class="EnlaceSimple" onclick="Ajax_CargarAccion_Evaluacion('<?php echo $arrRespuesta[0]["prospecto_id"]; ?>');">
                             <strong><?php echo $this->lang->line('legal_editar_reporte'); ?></strong>
                        </span>
                       
                    </td>
                </tr>

            </table>
        
        <?php
        }
        ?>
        
        <br />
        
        <table class="tblListas Centrado" style="width: 80%;" border="0">
                
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaCabecera">
                <th style="width:30%;">
                    <strong><?php echo $this->lang->line('requisitos_titulo_opcion'); ?></strong>
                </td>

                <th style="width:50%;">
                    <strong><?php echo $this->lang->line('requisitos_titulo_requisito'); ?></strong>
                </td>

                <th style="width:20%;">
                    
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaBlanca">
                <td style="text-align: justify;">
                    <strong><?php echo $this->lang->line('legal_opcion_vobo'); ?></strong>
                </td>

                <td style="text-align: justify;">
                    <?php echo $this->lang->line('legal_opcion_vobo_des'); ?>
                </td>

                <td style="text-align: center;">
                    <input id="seleccion1" name="opcion_seleccion" type="radio" class="" value="1" />
                    <label for="seleccion1" class=""><span></span>Seleccionar</label>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaBlanca">
                <td style="text-align: justify;">
                    <strong><?php echo $this->lang->line('legal_opcion_rechazar'); ?></strong>
                </td>

                <td style="text-align: justify;">
                    <?php echo $this->lang->line('legal_opcion_rechazar_des'); ?>
                </td>

                <td style="text-align: center;">
                    <input id="seleccion2" name="opcion_seleccion" type="radio" class="" value="2" />
                    <label for="seleccion2" class=""><span></span>Seleccionar</label>
                </td>
            </tr>

        </table>
        
        <br />
        
        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            
            <?php
            
                $direccion_bandeja = 'Menu/Principal';
            
                if(isset($_SESSION['direccion_bandeja_actual']))
                {
                    $direccion_bandeja = $_SESSION['direccion_bandeja_actual'];
                }
            
            ?>
            
            <a onclick="Ajax_CargarOpcionMenu('<?php echo $direccion_bandeja; ?>');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            
            <?php
            
            if($arrRespuesta[0]["empresa_categoria_codigo"] == 2)
            {
                echo "<a onclick='MostrarConfirmación();' class='BotonMinimalista'> " . $this->lang->line('BotonAceptar') . " </a>";
            }
            elseif($arrRespuesta[0]["empresa_categoria_codigo"] == 1 && $evaluacion_legal_reporte == TRUE)
            {
                echo "<a onclick='MostrarConfirmación();' class='BotonMinimalista'> " . $this->lang->line('BotonAceptar') . " </a>";
            }
            
            ?>

        </div>
        
        <div style="clear: both"></div>

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('legal_Pregunta'); ?></div>
        
            <div style="clear: both"></div>
        
            <br />

        <div class="PreguntaConfirmar">
            <?php echo $this->lang->line('PreguntaContinuar'); ?>
        </div>

        <div class="Botones2Opciones">
            <a onclick="OcultarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            
            <?php
            
            if($arrRespuesta[0]["empresa_categoria_codigo"] == 2)
            {
                echo "<a id='btnGuardarDatosLista' class='BotonMinimalista'> " . $this->lang->line('BotonAceptar') . " </a>";
            }
            elseif($arrRespuesta[0]["empresa_categoria_codigo"] == 1 && $evaluacion_legal_reporte == TRUE)
            {
                echo "<a id='btnGuardarDatosLista' class='BotonMinimalista'> " . $this->lang->line('BotonAceptar') . " </a>";
            }
            
            ?>

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