<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Mantenimiento/Aprobar/Guardar',
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
            Ajax_CargadoGeneralPagina('Mantenimiento/Verificar/NIT', 'resultadoNIT', "divErrorListaResultado", '', strParametros);
        }
    }
    
    function Ajax_CargarAccion_VerHorario() {
        
        var checked_radio = $('input[name="codigo_empresa"]:checked').val();
                
        if(checked_radio === undefined)
        {
            alert(' :: Primero Identifique la Empresa ::');
        }
        else
        {
            var arr = checked_radio.split('|');        
            var codigo_ejecutivo = arr[1];
            var strParametros = "&codigo=" + codigo_ejecutivo;
            Ajax_CargadoGeneralPagina('Ejecutivo/Lectura/Horario', 'divElementoFlotante', "divErrorListaResultadoFlotante", '', strParametros);
        }
    }
    
    function Ajax_CargarAccion_DetalleUsuario(tipo_codigo, codigo_usuario) {
        var strParametros = "&tipo_codigo=" + tipo_codigo + "&codigo_usuario=" + codigo_usuario;
        Ajax_CargadoGeneralPagina('Usuario/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleEmpresa(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Empresa/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    $('#solicitud_fecha_visita').datetimepicker({
	controlType: 'select',
	oneLine: true,
            timeFormat: 'HH:mm',
            minDate: 0,
            maxDate: 60
    });

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('SolicitudTitulo_aprobar'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('SolicitudSubitulo_aprobar'); ?></div>
        
        <div style="clear: both"></div>
        
        <br />
        
        <form id="FormularioRegistroLista" method="post">

        <input type="hidden" name="codigo_solicitud" id="codigo_solicitud" value="<?php echo $arrRespuesta[0]["solicitud_id"]; ?>" />
            
        <?php // *** PASO 1: CRITERIOS DEL PROSPECTO COMO EL NIT, CATEGORÍA DE COMERCIO Y EL TIPO DE PERSONA *** ?>

        <div class="FormularioSubtituloMediano"> <?php echo $this->lang->line('aprobar_mantenimiento_paso1'); ?></div>

        <div style="clear: both"></div>

        <br />

        <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_nit'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='<?php echo $this->lang->line('Ayuda_mantenimiento_nit'); ?>' data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">
                    
                    <table style="width: 100%;" border="0">
                        <tr>
                            <td style="width: 15%; text-align: center;">
                                
                                <span class="EnlaceSimple" onclick="Ajax_CargarAccion_VerificarNIT()">
                                    <strong><i class="fa fa-search" aria-hidden="true"></i> <?php echo $this->lang->line('solicitud_verificar_nit'); ?></strong>
                                </span>
                                
                            </td>
                            <td style="width: 85%;">
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

        <div class="FormularioSubtituloMediano"> <?php echo $this->lang->line('aprobar_mantenimiento_paso2'); ?></div>

        <div style="clear: both"></div>

        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

            <?php $strClase = "FilaBlanca"; ?>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_asignar_fecha'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='<?php echo $this->lang->line('Ayuda_asignar_fecha'); ?>' data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">
                    
                    <table style="width: 100%;" border="0">
                        <tr>
                            <td style="width: 15%; text-align: center;">
                                
                                <span class="EnlaceSimple" onclick="Ajax_CargarAccion_VerHorario()">
                                    <strong><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo $this->lang->line('solicitud_ver_calendario'); ?></strong>
                                </span>
                                
                            </td>
                            <td style="width: 85%;">
                                <?php echo $arrCajasHTML["solicitud_fecha_visita"]; ?>
                            </td>
                        </tr>
                    </table>                    
                    
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>" id="fila_categoria" name="fila_categoria">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_tiempo_visita'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='<?php echo $this->lang->line('Ayuda_tiempo_visita'); ?>' data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">
                    <select id="tiempo_visita" name="tiempo_visita">
                            <option value="30" mitext="30m">30 minutos</option>
                            <option value="60" mitext="60m">1 hora</option>
                            <option value="120" mitext="120m">2 horas</option>
                    </select>
                </td>
            </tr>

        </table>
        
        <br /><br /><br /><br />
        
        <?php // *** PASO 3: VERIFICACIÓN DE LA INFORMACIÓN DEL MANTENIMIENTO *** ?>
        
        <div class="FormularioSubtituloMediano"> <?php echo $this->lang->line('aprobar_mantenimiento_paso3'); ?></div>
        
        <div style="clear: both"></div>

        <br />

            <?php

            if(count($arrRespuesta[0]) > 0)
            {

            ?>

                <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                    <?php $strClase = "FilaGris"; ?>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">
                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_nombre_empresa'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_nombre_empresa"]; ?>
                        </td>
                    </tr>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">
                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_nit'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_nit"]; ?> ¿Es Correcto?
                        </td>
                    </tr>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">
                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_email'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_email"]; ?>
                        </td>
                    </tr>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_fecha'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_fecha"]; ?>
                        </td>

                    </tr>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_estado'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_estado"]; ?>
                        </td>

                    </tr>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('mantenimiento_tareas'); ?>
                        </td>

                        <td style="width: 70%;">

                            <?php                                            
                                if(isset($arrTareas[0]))
                                {
                                    foreach ($arrTareas as $key => $value) 
                                    {
                                        echo ' <i class="fa fa-wrench" aria-hidden="true"></i> ' . $value["tarea_detalle"];
                                        echo "<br />";
                                    }                                
                                }
                                else
                                {
                                    echo $this->lang->line('TablaNoRegistros');
                                }
                            ?>

                        </td>

                    </tr>

                    <?php

                    if($arrRespuesta[0]["solicitud_otro"] == 1)
                    {
                    ?>

                        <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">
                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('mantenimiento_otro'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["solicitud_otro_detalle"]; ?>
                            </td>
                        </tr>

                    <?php
                    }
                    ?>

                </table>

            <?php

            }

            else
            {            
                echo $this->lang->line('TablaNoResultados');
            }

            ?>
        
        </form>
            
        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Solicitud/Mantenimiento/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('aprobar_mantenimiento_Pregunta'); ?></div>
        
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