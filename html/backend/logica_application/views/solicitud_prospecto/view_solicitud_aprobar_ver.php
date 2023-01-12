<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Solicitud/Aprobar/Guardar',
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

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('SolicitudTitulo_aprobar'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('SolicitudSubitulo_aprobar'); ?></div>
        
        <div style="clear: both"></div>
        
        <br />
        
        <form id="FormularioRegistroLista" method="post">
        
        <?php // *** PASO 2: ASIGNACIÓN DE EJECUTIVO DE CUENTAS *** ?>

        <div class="FormularioSubtituloMediano"> <?php echo $this->lang->line('aprobar_solicitud_paso2'); ?></div>

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
                    
                    <table style="width: 100%;" border="0">
                        <tr>
                            <td style="width: 15%; text-align: center;">
                                
                                <span class="EnlaceSimple" onclick="Ajax_CargarAccion_VerMapaEjecutivos('-16.5463244,-68.1388459')">
                                    <strong><i class="fa fa-street-view" aria-hidden="true"></i> <?php echo $this->lang->line('solicitud_ver_mapa'); ?></strong>
                                </span>
                                
                            </td>
                            <td style="width: 85%;">
                                <?php

                                    if(isset($arrEjecutivo[0]))
                                    {
                                        echo html_select('codigo_ejecutivo', $arrEjecutivo, 'ejecutivo_id', 'usuario_nombre', '', '');
                                    }

                                ?>
                            </td>
                        </tr>
                    </table>                    
                    
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
                            <option value="15" mitext="15m">15 minutos</option>
                            <option value="30" mitext="30m">30 minutos</option>
                            <option value="60" mitext="60m">1 hora</option>
                            <option value="120" mitext="120m">2 horas</option>
                    </select>
                </td>
            </tr>

        </table>
        
        
        
        </form>
            
        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Solicitud/Afiliacion/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
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