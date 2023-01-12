<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Catalogo/Guardar',
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

    $('#catalogo_parent option[value=-1]').val("-1");
    $("#catalogo_parent").combobox();
    $('.custom-combobox-input').attr("placeholder", "--Seleccione el valor o escriba para filtrar--");
    
    function catalogo_nuevo() {
        
        $("#catalogo_nuevo_extra").hide();
        $("#catalogo_tipo_codigo").hide();
        
        if($("#catalogo_nuevo").val() == '1')
        {
            $('#catalogo_tipo_extra').attr("placeholder", ":: Escriba la Nueva Categoría ::");
            $("#catalogo_nuevo_extra").show();
        }
        else
        {
            $("#catalogo_tipo_codigo").show();
        }
    }

    $("#catalogo_nuevo").change(function(){
        catalogo_nuevo();
    });

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('CatalogoTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('CatalogoSubtitulo'); ?> <br /><br /><span style="color: red;">TOME EN CONSIDERACIÓN QUE LOS CAMBIOS QUE REALICE EN LOS CATÁLOGOS PUEDE AFECTAR A LOS REGISTROS EXISTENTES POR LO QUE SÓLO DEBE MODIFICAR LA DATA SI ES EXTRICAMENTE NECESARIO. REGISTRE CORRECTAMENTE EL CÓDIGO DEL CATÁLOGO ASÍ COMO SU DEPENDENCIA (SI CORRESPONDE)</span></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="catalogo_id" value="<?php if(isset($arrRespuesta[0]["catalogo_id"])){ echo $arrRespuesta[0]["catalogo_id"]; } ?>" />

            <input type="hidden" name="tipo_accion" value="<?php echo $tipo_accion; ?>" />
            
        <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('catalogo_tipo_codigo'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='Seleccione la categoría del Catálogo registrado en el sistema' data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">
                    
                    <?php
                    
                    if(isset($arrCatalogos[0])) 
                    {
                        if($tipo_accion == 1 && isset($arrRespuesta[0]["catalogo_id"]))
                        {
                            $catalogo_tipo_codigo = $arrRespuesta[0]["catalogo_tipo_codigo"];
                        }
                        else
                        {
                            $catalogo_tipo_codigo = '';
                        }
                        
                        echo html_select('catalogo_tipo_codigo', $arrCatalogos, 'catalogo_tipo_codigo', 'catalogo_tipo_codigo', '', $catalogo_tipo_codigo);
                    }
                        
                    if($tipo_accion == 0)
                    {
                    ?>
                    
                        <table style="width: 100%;" border="0">
                            <tr>
                                <td valign="middle" style="width: 40%; text-align: right; font-weight: bold;">
                                    ¿Nueva Categoría?
                                </td>
                                <td valign="top" style="width: 60%;">
                                    <?php echo $arrCajasHTML["catalogo_nuevo"] . '<br /><span id="catalogo_nuevo_extra" style="display: none;">' . $arrCajasHTML["catalogo_tipo_extra"] . '</span>'; ?>
                                </td>
                            </tr>
                        </table>
                    
                    <?php
                    }
                    ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('catalogo_parent'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php                                            
                        if(count($arrListadoDepende[0]) > 0)
                        {
                            $valor_parent = '-1';
                            if($tipo_accion == 1)
                            {
                                $valor_parent = $catalogo_parent;
                            }
                            
                            echo html_select('catalogo_parent', $arrListadoDepende, 'lista_codigo', 'lista_valor', '', '');
                        }
                        else
                        {
                            echo $this->lang->line('TablaNoRegistros');
                        }
                        
                        if(isset($arrRespuesta[0]["catalogo_id"])){ echo $arrRespuesta[0]["catalogo_parent_detalle"]; }
                    ?>
                    
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('catalogo_codigo'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["catalogo_codigo"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('catalogo_descripcion'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["catalogo_descripcion"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('catalogo_estado'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["catalogo_estado"]; ?>
                </td>

            </tr>

        </table>

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Catalogo/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
        

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('conf_catalogo_Pregunta'); ?></div>
        
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