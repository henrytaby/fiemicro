<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Solicitud/Afiliacion/Guardar',
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
    
    function Ajax_CargarAccion_MapaSolicitud(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Solicitud/Zona/Ver', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }

    // Cargado de Selects
    
    $("#empresa_departamento").change(function(){
        var parent_codigo = $(this).val();
        $.ajax({
            url: 'Registro/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'CIU', parent_tipo:'DEP'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#empresa_municipio").empty();
                $("#empresa_municipio").append("<option value='-1'>-- Seleccione el Municipio/Ciudad --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#empresa_municipio").append("<option value='"+id+"'>"+name+"</option>");
                }
                $("#empresa_zona").empty();
                $("#empresa_zona").append("<option value='-1'>-- Antes seleccione el Municipio/Ciudad --</option>");
            }
        });
    });
    
    $("#empresa_municipio").change(function(){
        var parent_codigo = $(this).val();
        $.ajax({
            url: 'Registro/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'ZON', parent_tipo:'CIU'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#empresa_zona").empty();
                $("#empresa_zona").append("<option value='-1'>-- Seleccione la Zona --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#empresa_zona").append("<option value='"+id+"'>"+name+"</option>");
                }
            }
        });
    });

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('EditarSolicitudTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('EditarSolicitudSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0])){ echo $arrRespuesta[0]["solicitud_id"]; } ?>" />

            <input type="hidden" name="tipo_accion" value="<?php echo $tipo_accion; ?>" />
            
        <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_nombre_persona'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["solicitud_nombre_persona"]; ?>
                </td>
            </tr>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_nombre_empresa'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["solicitud_nombre_empresa"]; ?>
                </td>
            </tr>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_departamento_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <?php                                            
                        if(isset($arrDepartamento[0]) && count($arrDepartamento[0]) > 0)
                        {
                            $valor = $arrRespuesta[0]['solicitud_departamento_codigo'];
                            
                            echo html_select('empresa_departamento', $arrDepartamento, 'lista_codigo', 'lista_valor', '', $valor);
                        }
                        else
                        {
                            echo html_select('empresa_departamento', $arrVacio, 'lista_codigo', 'lista_valor', 'SINSELECCIONAR', $valor);
                        }
                    ?>
                    
                </td>
            </tr>
            
            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_municipio_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <?php
                        if(isset($arrCiudad[0]) && count($arrCiudad[0]) > 0)
                        {
                            $valor = $arrRespuesta[0]['solicitud_ciudad_codigo'];
                            
                            echo html_select('empresa_municipio', $arrCiudad, 'lista_codigo', 'lista_valor', '', $valor);
                        }
                        else
                        {
                            echo html_select('empresa_municipio', $arrVacio, 'lista_codigo', 'lista_valor', 'SINSELECCIONAR', $valor);
                        }
                    ?>
                    
                </td>
            </tr>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_zona_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <?php                                            
                        if(isset($arrZona[0]) && count($arrZona[0]) > 0)
                        {
                            $valor = $arrRespuesta[0]['solicitud_zona_codigo'];
                            
                            echo html_select('empresa_zona', $arrZona, 'lista_codigo', 'lista_valor', '', $valor);
                        }
                        else
                        {
                            echo html_select('empresa_zona', $arrVacio, 'lista_codigo', 'lista_valor', 'SINSELECCIONAR', $valor);
                        }
                    ?>
                    
                </td>
            </tr>
            
            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_telefono'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["solicitud_telefono"]; ?>
                </td>

            </tr>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_email'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["solicitud_email"]; ?>
                </td>

            </tr>
            
            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_direccion_geo'); ?> <i class="fa fa-map-marker" aria-hidden="true"></i>
                </td>

                <td style="width: 70%;">                    
                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_MapaSolicitud('<?php echo $_SESSION['coordenadas_solicitud']; ?>')">
                        <strong><i class="fa fa-street-view" aria-hidden="true"></i><?php echo $this->lang->line('solicitud_direccion_geo_des'); ?>
                    </span>
                    
                    <br />
                </td>

            </tr>
            
            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_direccion_literal'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["solicitud_direccion_literal"]; ?>
                </td>

            </tr>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_rubro'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <?php                                            
                        if(count($arrRubro[0]) > 0)
                        {
                            $valor_parent = '-1';
                            if($tipo_accion == 1)
                            {
                                $valor_parent = $arrRespuesta[0]['solicitud_rubro_codigo'];
                            }
                            
                            echo html_select('catalogo_rubro', $arrRubro, 'catalogo_codigo', 'catalogo_descripcion', '', $valor_parent);
                        }
                        else
                        {
                            echo $this->lang->line('TablaNoRegistros');
                        }
                    ?>
                    
                </td>

            </tr>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_servicios'); ?>
                </td>

                <td style="width: 70%;">

                    <br />
                        
                    <?php

                        if(isset($arrServicios[0]))
                        {
                            $i = 0;
                            $checked = '';

                            foreach ($arrServicios as $key => $value) 
                            {
                                $checked = '';
                                if($value["servicio_asignado"])
                                {
                                    $checked = 'checked="checked"';
                                }

                                echo '<div class="divOpciones">';
                                echo '<input id="servicio' . $i , '" type="checkbox" name="servicio_list[]" '. $checked .' value="' . $value["servicio_id"] . '">';
                                echo '<label for="servicio' . $i , '"><span></span>' . $value["servicio_detalle"] . '</label>';
                                echo '</div>';

                                $i++;
                            }
                        }

                    ?>
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
            <div class="PreguntaTexto "><?php echo $this->lang->line('editar_solicitud_Pregunta'); ?></div>
        
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