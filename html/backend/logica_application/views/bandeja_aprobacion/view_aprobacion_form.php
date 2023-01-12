<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Bandeja/Aprobacion/Guardar',
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

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('AprobacionFormTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('AprobacionFormSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["prospecto_id"])){ echo $arrRespuesta[0]["prospecto_id"]; } ?>" />

        <div class="FormularioSubtituloMediano" style="margin-right: 10%;"> <?php echo $this->lang->line('aprobar_paso1'); ?></div>

        <div style="clear: both"></div>

        <br />
            
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

                <td style="width: 70%; font-weight: bold;">
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
        
        <br /><br />
        
        <div class="FormularioSubtituloMediano" style="margin-right: 10%;"> <?php echo $this->lang->line('aprobar_paso2'); ?></div>

        <div style="clear: both"></div>

        <br />
            
        <?php 
        
        if($falta_info == 1)
        {
             echo "<div class='mensaje_alerta'> " . $this->lang->line('aprobacion_advertencia') . $falta_info_texto . " </div> <br />"; 
        }
        
        ?>
        
        <br />
        
        <?php 
        
        if($tipo_empresa == 1)
        {
            // Comercio        
        ?>
            <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

                <?php $strClase = "FilaGris"; ?>

                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_nit'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_nit"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_adquiriente_detalle'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_adquiriente_detalle"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_tipo_sociedad_detalle'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_tipo_sociedad_detalle"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_nombre_legal'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_nombre_legal"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_nombre_fantasia'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_nombre_fantasia"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_mcc_detalle'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_mcc_detalle"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_rubro_detalle'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_rubro_detalle"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_perfil_comercial_detalle'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_perfil_comercial_detalle"]; ?>
                    </td>
                </tr>

            </table>
        
        <?php 
        }
        
        if($tipo_empresa == 2)
        {
            // Establecimiento        
        ?>
        
            <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

                <?php $strClase = "FilaGris"; ?>

                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_nombre_establecimiento'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_nombre_establecimiento"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_denominacion_corta'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_denominacion_corta"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_ha_desde'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_ha_desde"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_ha_hasta'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_ha_hasta"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_dias_atencion'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php
                            
                        if (isset($arrDias[0])) 
                        {
                            foreach ($arrDias as $key => $value) 
                            {
                                switch ($value) {
                                    case 1:

                                        echo ' - Lunes <br />';

                                        break;

                                    case 2:

                                        echo ' - Martes <br />';

                                        break;

                                    case 3:

                                        echo ' - Miércoles <br />';

                                        break;

                                    case 4:

                                        echo ' - Jueves <br />';

                                        break;

                                    case 5:

                                        echo ' - Viernes <br />';

                                        break;

                                    case 6:

                                        echo ' - Sábado <br />';

                                        break;

                                    case 7:

                                        echo ' - Domingo <br />';

                                        break;

                                    default:
                                        break;
                                }
                            }
                        }

                        ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_medio_contacto_detalle'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_medio_contacto_detalle"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_dato_contacto'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_dato_contacto"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_departamento_detalle'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_departamento_detalle"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_municipio_detalle'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_municipio_detalle"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_zona_detalle'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_zona_detalle"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_tipo_calle_detalle'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_tipo_calle_detalle"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_calle'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_calle"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_numero'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_numero"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_info_adicional'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrResultado[0]["empresa_info_adicional"]; ?>
                    </td>
                </tr>

            </table>        
        
        <?php 
        }
        ?>
        
        <br /><br />
        
        <div class="FormularioSubtituloMediano" style="margin-right: 10%;"> <?php echo $this->lang->line('aprobar_paso3'); ?></div>

        <div style="clear: both"></div>

        <br />
        
        <table class="tblListas Centrado" style="width: 80%;" border="0">
                
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaCabecera">
                <th style="width:75%;">
                    <strong><?php echo $this->lang->line('requisitos_titulo_requisito'); ?></strong>
                </td>

                <th style="width:25%;">
                    <strong><?php echo $this->lang->line('requisitos_titulo_opcion'); ?></strong>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaBlanca">
                <td style="text-align: justify;">
                    <?php echo $this->lang->line('aprobacion_1_des'); ?>
                </td>

                <td style="text-align: center;">
                    
                    <div style="float: left;">
                        
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        
                        <input id="seleccion1-si" name="opcion_seleccion1" type="radio" class="" value="1" />
                        <label for="seleccion1-si" class=""><span></span>Si</label>
                    
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                
                    </div>
                    
                    <div style="float: left;">
                        
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        
                        <input id="seleccion1-no" name="opcion_seleccion1" type="radio" class="" checked="checked" value="0" />
                        <label for="seleccion1-no" class=""><span></span>No</label>
                        
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        
                    </div>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaBlanca">
                <td style="text-align: justify;">
                    <?php echo $this->lang->line('aprobacion_2_des'); ?>
                </td>

                <td style="text-align: center;">
                    
                    <div style="float: left;">
                                       
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        
                        <input id="seleccion2-si" name="opcion_seleccion2" type="radio" class="" value="1" />
                        <label for="seleccion2-si" class=""><span></span>Si</label>
                        
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                
                    </div>
                    
                    <div style="float: left;">
                        
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        
                        <input id="seleccion2-no" name="opcion_seleccion2" type="radio" class="" checked="checked" value="0" />
                        <label for="seleccion2-no" class=""><span></span>No</label>
                        
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        
                    </div>
                </td>
            </tr>

        </table>
        
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
            
            if($falta_info == 0)
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
            <div class="PreguntaTexto "><?php echo $this->lang->line('aprobacion_Pregunta'); ?></div>
        
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
            
            if($falta_info == 0)
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