<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Rol/Guardar',
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
    
    function MarcarOpcionesFiltroLista(){
        var opciones = $('#campoFiltroLista').find("input[type=checkbox]");
        var opcionesMarcadas = $('#campoFiltroLista').find("input[type=checkbox]:checked");
        if (opciones.length == opcionesMarcadas.length) {
            // Desmarcar
            opciones.prop("checked",false);
            opciones.attr("checked",false);
        } else
        {
            // Marcar
            opciones.prop("checked",true);
            opciones.attr("checked",true);
        }
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('RolTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('RolSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["estructura_codigo"])){ echo $arrRespuesta[0]["estructura_codigo"]; } ?>" />

            <input type="hidden" name="tipo_accion" value="<?php echo $tipo_accion; ?>" />
            
        <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('estructura_nombre'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["estructura_nombre"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('estructura_detalle'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["estructura_detalle"]; ?>
                </td>

            </tr>
        </table>
        
        </table>
            
        <br />
            
        <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    Establecer Rol para <?php echo $this->lang->line('Usuario_perfil_app'); ?>
                    
                    <span class="AyudaTooltip" data-balloon-length="large" data-balloon="<?php echo $this->lang->line('Rol_perfil_app'); ?>" data-balloon-pos="right"> </span>
                    
                </td>

                <td style="width: 70%;">                
                    <?php
                    
                        if(isset($arrPerfilApp[0]))
                        {
                            foreach ($arrPerfilApp as $key => $value) 
                            {
                            ?>
                                <div class="divOpciones">
                                    <input id="perfil_app<?php echo $value["perfil_app_id"]; ?>" name="perfil_app" type="radio" class="" <?php if(isset($arrRespuesta[0]["perfil_app_id"]) && $arrRespuesta[0]["perfil_app_id"] == $value["perfil_app_id"]){ echo "checked='checked'"; } ?> value="<?php echo $value["perfil_app_id"]; ?>" />
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
            
        <br />
            
        <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td valign="top" style="width: 30%; font-weight: bold; text-align: center;">
                        
                        <br /><br />
                        
                        <?php echo $this->lang->line('estructura_menu'); ?>
                        
                        <br /><br />
                    
                        <a onclick="MarcarOpcionesFiltroLista()" id="FiltroReporteFilaListaBotonMarcar" style="font-weight: bold;"><i class="fa fa-object-group" aria-hidden="true"></i> Marcar/Desmarcar todos</a>
                        
                    </td>

                    <td style="width: 70%;" id="campoFiltroLista">                        
                        
                        <br />
                        
                        <?php
                        
                            if(isset($arrMenu[0]))
                            {
                                $arrMenu = $this->mfunciones_generales->ArrGroupBy($arrMenu, 'menu_orden');
                                
                                $i = 0;
                                $checked = '';
                                
                                foreach ($arrMenu as $key => $values) 
                                {
                                    $menu_header = '';
                                    
                                    switch ($key) {
                                        case 0:

                                            $menu_header = 'Administración y Configuración <i class="fa fa-cogs" aria-hidden="true"></i>';
                                            
                                            break;
                                        
                                        case 1:

                                            $menu_header = 'Módulos de Parametrización  <i class="fa fa-pencil-square-o" aria-hidden="true"></i>';
                                            
                                            break;

                                        case 2:

                                            $menu_header = 'Módulos de Lógica de Negocio <i class="fa fa-lightbulb-o" aria-hidden="true"></i>';
                                            
                                            break;
                                        
                                        case 3:

                                            $menu_header = 'Consultas, Seguimiento y Reportería <i class="fa fa-line-chart" aria-hidden="true"></i>';
                                            
                                            break;
                                        
                                        case 4:

                                            $menu_header = 'Onboarding Digital <i class="fa fa-user-circle" aria-hidden="true"></i>';
                                        
                                        default:
                                            break;
                                    }
                                    
                                    echo "<div style='font-style: italic; font-weight: bold; text-align: center;'>" . $menu_header . " &nbsp;&nbsp; </div> <br />";
                                
                                    foreach ($values as $value) 
                                    {
                                        $checked = '';
                                        if($value["menu_asignado"])
                                        {
                                            $checked = 'checked="checked"';
                                        }

                                        echo '<div class="divOpciones">';
                                        echo '<span class="AyudaTooltip" data-balloon-length="medium" data-balloon="' . $value["menu_descripcion"] . '" data-balloon-pos="right"> </span>';
                                        echo '<input id="menu' . $i , '" type="checkbox" name="menu_list[]" '. $checked .' value="' . $value["menu_id"] . '">';
                                        echo '<label for="menu' . $i , '"><span></span>' . $value["menu_nombre"] . '</label>';
                                        echo '</div>';

                                        $i++;
                                    }
                                    
                                    echo '<div style="clear: both"></div><br />';
                                }
                            }
                            
                        ?>
                        
                    </td>

                </tr>
            
        </table>

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Rol/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
        

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('estructura_Pregunta'); ?></div>
        
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