<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Afiliador/Aprobar/Guardar',
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
            Ajax_CargadoGeneralPagina('Solicitud/Verificar/NIT', 'divElementoFlotante', "divErrorListaResultado", '', strParametros);
        }
    }
    
    function HideTipoPersona(){
        //document.getElementById('fila_categoria').style.display = 'none';
        $("#fila_categoria").addClass("ocultar_elemento");
    }
    
    function ShowTipoPersona(){
        //document.getElementById('fila_categoria').style.display = 'block';
        $("#fila_categoria").removeClass("ocultar_elemento");
    }
    
    function Ajax_CargarAccion_VerMapaEjecutivos(codigo_ejecutivo) {
        var strParametros = "&codigo=" + codigo_ejecutivo;
        Ajax_CargadoGeneralPagina('Solicitud/Mapa/Ver', 'divElementoFlotante', "divErrorListaResultadoFlotante", '', strParametros);
    }    
    
    function Ajax_CargarAccion_VerHorario() {
        var codigo_ejecutivo = $('select[name=codigo_ejecutivo]').val();
        
        if(codigo_ejecutivo == -1)
        {
            alert(' :: Debe Seleccionar un Ejecutivo de Cuenta ::');
        }
        else
        {        
            var strParametros = "&codigo=" + codigo_ejecutivo;
            Ajax_CargadoGeneralPagina('Ejecutivo/Lectura/Horario', 'divElementoFlotante', "divErrorListaResultadoFlotante", '', strParametros);
        }
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

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo '¡Excelente! Vamos a continuar con el Proceso' . $nombre_region; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('SolicitudSubitulo_aprobar'); ?></div>
        
        <div style="clear: both"></div>
        
        <br />
        
        <form id="FormularioRegistroLista" method="post">

        <input type="hidden" name="codigo_solicitud" id="codigo_solicitud" value="<?php echo $arrRespuesta[0]["terceros_id"]; ?>" />
        
		<?php // *** PASO 3: VERIFICACIÓN DE LA INFORMACIÓN DEL PROSPECTO *** ?>
        
        <div class="FormularioSubtituloMediano"> Primero - Revise la Información Registrada </div>
        
        <div style="clear: both"></div>

        <br />

            <?php

            if(count($arrRespuesta[0]) > 0)
            {

            ?>

                <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                    <?php $strClase = "FilaGris"; ?>

					<tr class="<?php echo $strClase; ?>">

                        <td colspan="2" style="font-weight: bold; font-size: 1.5em;">
                            DATOS DE IDENTIFICACION
                        </td>
                    </tr>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_fecha'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["terceros_fecha"]; ?>
                        </td>

                    </tr>
					
                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_nombre_persona'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["nombre_persona"]; ?>
                        </td>
                    </tr>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                    <tr class="<?php echo $strClase; ?>">
                        <td style="width: 30%; font-weight: bold;">
                            C.I.
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["ci"]; ?>
                        </td>
                    </tr>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                    <tr class="<?php echo $strClase; ?>">
                        <td style="width: 30%; font-weight: bold;">
                            Estado Civil
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["estado_civil"]; ?>
                        </td>
                    </tr>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            Teléfono o Móvil
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["telefono"]; ?>
                        </td>

                    </tr>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_email'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["email"]; ?>
                        </td>

                    </tr>

					<?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                    <tr class="<?php echo $strClase; ?>">
                        <td style="width: 30%; font-weight: bold;">
                            NIT
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["nit"]; ?>
                        </td>
                    </tr>

					<tr class="<?php echo $strClase; ?>">

                        <td colspan="2" style="font-weight: bold; font-size: 1.5em;">
                            DATOS DEMOGRÁFICOS
                        </td>
                    </tr>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            País de residencia
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["pais"]; ?>
                        </td>

                    </tr>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            Profesión
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["profesion"]; ?>
                        </td>

                    </tr>
					
					<tr class="<?php echo $strClase; ?>">

                        <td colspan="2" style="font-weight: bold; font-size: 1.5em;">
                            DATOS ECONOMICOS
                        </td>
                    </tr>
					
					<?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            Nivel de ingresos o Promedio de ingresos mensuales
                        </td>

                        <td style="width: 70%;">
                            <?php echo number_format($arrRespuesta[0]["ingreso"], 2, '.', ','); ?>
                        </td>

                    </tr>
					
					<tr class="<?php echo $strClase; ?>">

                        <td colspan="2" style="font-weight: bold; font-size: 1.5em;">
                            DATOS DOMICILIO
                        </td>
                    </tr>
					
					<?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            Geolocalización Domicilio
                        </td>

                        <td style="width: 70%;">
							<a href="https://maps.google.com/?q=<?php echo $arrRespuesta[0]["mis_coordenadas_mapa"]; ?>" target="_blank"> <i class="fa fa-map-marker" aria-hidden="true"></i> Ver Mapa </a>
                        </td>

                    </tr>
					
					<?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            Geolocalización Domicilio
                        </td>

                        <td style="width: 70%;">
                            <a href="https://maps.google.com/?q=<?php echo $arrRespuesta[0]["mis_coordenadas_mapa2"]; ?>" target="_blank"> <i class="fa fa-map-marker" aria-hidden="true"></i> Ver Mapa </a>
                        </td>

                    </tr>
					
					<tr class="<?php echo $strClase; ?>">

                        <td colspan="2" style="font-weight: bold; font-size: 1.5em;">
                            REFERENCIAS PERSONALES
                        </td>
                    </tr>
					
					<?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            Cónyuge - Nombre
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["conyugue_nombre"]; ?>
                        </td>

                    </tr>
					
					<?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            Cónyuge - Actividad
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["conyugue_actividad"]; ?>
                        </td>

                    </tr>
					
					<?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            Referencias
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["referencias"]; ?>
                        </td>

                    </tr>
					
					<tr class="<?php echo $strClase; ?>">

                        <td colspan="2" style="font-weight: bold; font-size: 1.5em;">
                            EMPLEOS
                        </td>
                    </tr>
					
					<?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            Actividad u ocupación principal
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["actividad_principal"]; ?>
                        </td>

                    </tr>
					
					<?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            Lugar de Trabajo
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["lugar_trabajo"]; ?>
                        </td>

                    </tr>
					
					<?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            Cargo Actual
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["cargo"]; ?>
                        </td>

                    </tr>
					
					<?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            Año de Ingreso
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["ano_ingreso"]; ?>
                        </td>

                    </tr>
					
					<tr class="<?php echo $strClase; ?>">

                        <td colspan="2" style="font-weight: bold; font-size: 1.5em;">
                            FACILIDAD DE REGISTRO
                        </td>
                    </tr>
					
					<?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            Preferencia del Cliente
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["llevar"]; ?>
                        </td>

                    </tr>
					
                </table>

            <?php

            }

            else
            {            
                echo $this->lang->line('TablaNoResultados');
            }

            ?>
		
        <?php // *** PASO 1: CRITERIOS DEL PROSPECTO COMO EL NIT, CATEGORÍA DE COMERCIO Y EL TIPO DE PERSONA *** ?>

        <div class="FormularioSubtituloMediano"> Segundo - Parámetros de Negocio</div>

        <div style="clear: both"></div>

        <br />

        <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_nit'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='<?php echo $this->lang->line('Ayuda_solicitud_nit'); ?>' data-balloon-pos="right"> </span>
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
                                <?php echo $arrRespuesta[0]["nit"]; ?>
                            </td>
                        </tr>
                    </table>                    
                    
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_categoria_empresa'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='<?php echo $this->lang->line('Ayuda_solicitud_catergoria'); ?>' data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">

                    <input id="categoria_empresa1" name="categoria_empresa" type="radio" class="" checked='checked' value="1" onclick="ShowTipoPersona();" />
                    <label for="categoria_empresa1" class=""><span></span><?php echo $this->lang->line('categoria_empresa_comercio'); ?></label>

                    &nbsp;

                    <input id="categoria_empresa2" name="categoria_empresa" type="radio" class="" value="2" onclick="HideTipoPersona();" />
                    <label for="categoria_empresa2" class=""><span></span><?php echo $this->lang->line('categoria_empresa_sucrusal'); ?></label>

                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>" id="fila_categoria" name="fila_categoria">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_tipo_persona'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='<?php echo $this->lang->line('Ayuda_solicitud_tipo'); ?>' data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">
                    <?php

                        if(isset($arrTipoPersona[0]))
                        {
                            echo html_select('tipo_persona', $arrTipoPersona, 'tipo_persona_id', 'tipo_persona_nombre', '', $arrRespuesta[0]["tipo_persona_id_codigo"]);
                        }

                    ?>
                </td>
            </tr>

        </table>

        <div id="divErrorListaResultadoFlotante" class="mensajeBD"> </div>
        
        <br /><br /><br /><br />
        
        <?php // *** PASO 2: ASIGNACIÓN DE EJECUTIVO DE CUENTAS *** ?>

        <div class="FormularioSubtituloMediano"> Por último - Seleccione al Agente para Asignación </div>

        <div style="clear: both"></div>

        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_mapa_ejecutivos'); ?>
                </td>

                <td style="width: 70%;">
                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_VerMapaEjecutivos('<?php echo $arrRespuesta[0]["mis_coordenadas_mapa"]; ?>')">
						<strong><i class="fa fa-street-view" aria-hidden="true"></i> <?php echo $this->lang->line('solicitud_ver_mapa'); ?></strong>
					</span>
                    <?php
                        echo $arrEjecutivo;
                    ?>
                    
                </td>

            </tr>

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
        
        </form>
            
        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Afiliador/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('aprobar_solicitud_Pregunta'); ?></div>
        
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