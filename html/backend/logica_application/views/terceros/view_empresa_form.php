<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Afiliador/Empresa/Guardar',
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

    // Horario de atención
    
    var startTimeTextBox1 = $('#empresa_ha_desde');
    var endTimeTextBox1 = $('#empresa_ha_hasta');

    $.timepicker.timeRange(
        startTimeTextBox1,
        endTimeTextBox1,
        {
            minInterval: (1000*60*60), // 1hr
            timeFormat: 'HH:mm',
            start: {}, // start picker options
            end: {} // end picker options
        }
    );
    
    <?php
    
        if($arrRespuesta[0]["empresa_categoria_codigo"] == 1)
        {
            echo '$("#tb_comercio").show();';
            echo '$("#tb_sucursal").hide();';
        }
        
        if($arrRespuesta[0]["empresa_categoria_codigo"] == 2)
        {
            echo '$("#tb_comercio").hide();';
            echo '$("#tb_sucursal").show();';
        }
    
    ?>
    
    // Cargado de Selects
    
    $("#empresa_mcc").change(function(){
        var parent_codigo = $(this).val();
        $.ajax({
            url: 'Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'RUB', parent_tipo:'MCC'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#empresa_rubro").empty();
                $("#empresa_rubro").append("<option value='-1'>-- Seleccione el Rubro --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#empresa_rubro").append("<option value='"+id+"'>"+name+"</option>");
                }
            }
        });
    });
    
    $("#empresa_departamento").change(function(){
        var parent_codigo = $(this).val();
        $.ajax({
            url: 'Afiliador/Select/Cargar',
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
            url: 'Afiliador/Select/Cargar',
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

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> Actualización de Información de la Empresa - <?php echo $arrRespuesta[0]["empresa_categoria_detalle"] . $nombre_region; ?> </div>
            <div class="FormularioSubtituloComentarioNormal "> En este apartado podrá actualizar la información registrada de la Empresa. Todos los campos son mandatorios. <br /><br /> La información de la Empresa que será actualizada podría generar conflicto en instancias que realizaron una evaluación previa al cambio. </div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" name="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="empresa_id" value="<?php if(isset($arrRespuesta[0]["empresa_id"])){ echo $arrRespuesta[0]["empresa_id"]; } ?>" />
            <input type="hidden" name="empresa_categoria" value="<?php if(isset($arrRespuesta[0]["empresa_categoria_codigo"])){ echo $arrRespuesta[0]["empresa_categoria_codigo"]; } ?>" />
        
        <table id="tb_comercio" class="tablaresultados Mayuscula" style="width: 100%;" border="0">
            
            <?php $strClase = "FilaBlanca"; ?>
        
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_nit'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["empresa_nit"]; ?>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_tipo_sociedad_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <?php                                            
                        if(isset($arrTipoSociedad[0]) && count($arrTipoSociedad[0]) > 0)
                        {
                            $valor = $arrRespuesta[0]['empresa_tipo_sociedad_codigo'];
                            
                            echo html_select('empresa_tipo_sociedad', $arrTipoSociedad, 'lista_codigo', 'lista_valor', '', $valor);
                        }
                        else
                        {
                            echo html_select('empresa_tipo_sociedad', $arrVacio, 'lista_codigo', 'lista_valor', 'SINSELECCIONAR', $valor);
                        }
                    ?>
                    
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_nombre_legal'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["empresa_nombre_legal"]; ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_nombre_fantasia'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["empresa_nombre_fantasia"]; ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <i class="fa fa-link" aria-hidden="true"></i> <?php echo $this->lang->line('empresa_mcc_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    <?php                                            
                        if(isset($arrMCC[0]) && count($arrMCC[0]) > 0)
                        {
                            $valor = $arrRespuesta[0]['empresa_mcc_codigo'];
                            
                            echo html_select('empresa_mcc', $arrMCC, 'lista_codigo', 'lista_valor', '', $valor);
                        }
                        else
                        {
                            echo html_select('empresa_mcc', $arrVacio, 'lista_codigo', 'lista_valor', 'SINSELECCIONAR', $valor);
                        }
                    ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <i class="fa fa-link" aria-hidden="true"></i> <?php echo $this->lang->line('empresa_rubro_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    <?php                                            
                        if(isset($arrRubro[0]) && count($arrRubro[0]) > 0)
                        {
                            $valor = $arrRespuesta[0]['empresa_rubro_codigo'];
                            
                            echo html_select('empresa_rubro', $arrRubro, 'lista_codigo', 'lista_valor', '', $valor);
                        }
                        else
                        {
                            echo html_select('empresa_rubro', $arrVacio, 'lista_codigo', 'lista_valor', 'SINSELECCIONAR', $valor);
                        }
                    ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_perfil_comercial_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    <?php                                            
                        if(isset($arrPerfilComercial[0]) && count($arrPerfilComercial[0]) > 0)
                        {
                            $valor = $arrRespuesta[0]['empresa_perfil_comercial_codigo'];
                            
                            echo html_select('empresa_perfil_comercial', $arrPerfilComercial, 'lista_codigo', 'lista_valor', '', $valor);
                        }
                        else
                        {
                            echo html_select('empresa_perfil_comercial', $arrVacio, 'lista_codigo', 'lista_valor', 'SINSELECCIONAR', $valor);
                        }
                    ?>
                </td>
            </tr>

        </table>
        
        <br />
            
        <table id="tb_sucursal" class="tablaresultados Mayuscula" style="width: 100%;" border="0">
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_nombre_establecimiento'); ?>
                </td>

                <td style="width: 70%;">

                    <?php echo $arrCajasHTML["empresa_nombre_establecimiento"]; ?>

                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_denominacion_corta'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["empresa_denominacion_corta"]; ?>
                </td>
            </tr>

        </table>
        
        <br />
            
        <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_nombre_referencia'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["empresa_nombre_referencia"]; ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_ha_desde'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["empresa_ha_desde"]; ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_ha_hasta'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["empresa_ha_hasta"]; ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_dias_atencion'); ?>
                </td>

                <td style="width: 70%;">

                    <div class="divOpciones">
                        
                        <?php $seleccion = ''; if (in_array("1", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                        <input id="d1" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="1">
                        <label for="d1"><span></span>Lunes</label>

                    </div>
                    
                    <div class="divOpciones">

                        <?php $seleccion = ''; if (in_array("2", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                        <input id="d2" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="2">
                        <label for="d2"><span></span>Martes</label>

                    </div>
                    
                    <div class="divOpciones">

                        <?php $seleccion = ''; if (in_array("3", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                        <input id="d3" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="3">
                        <label for="d3"><span></span>Miércoles</label>

                    </div>
                    
                    <div class="divOpciones">

                        <?php $seleccion = ''; if (in_array("4", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                        <input id="d4" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="4">
                        <label for="d4"><span></span>Jueves</label>

                    </div>
                    
                    <div class="divOpciones">

                        <?php $seleccion = ''; if (in_array("5", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                        <input id="d5" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="5">
                        <label for="d5"><span></span>Viernes</label>

                    </div>
                    
                    <div class="divOpciones">

                        <?php $seleccion = ''; if (in_array("6", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                        <input id="d6" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="6">
                        <label for="d6"><span></span>Sábado</label>

                    </div>
                    
                    <div class="divOpciones">

                        <?php $seleccion = ''; if (in_array("7", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                        <input id="d7" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="7">
                        <label for="d7"><span></span>Domingo</label>
                        
                    </div>

                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_medio_contacto_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    <?php                                            
                        if(isset($arrMedioContacto[0]) && count($arrMedioContacto[0]) > 0)
                        {
                            $valor = $arrRespuesta[0]['empresa_medio_contacto_codigo'];
                            
                            echo html_select('empresa_medio_contacto', $arrMedioContacto, 'lista_codigo', 'lista_valor', '', $valor);
                        }
                        else
                        {
                            echo html_select('empresa_medio_contacto', $arrVacio, 'lista_codigo', 'lista_valor', 'SINSELECCIONAR', $valor);
                        }
                    ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_dato_contacto'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["empresa_dato_contacto"]; ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_email'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["empresa_email"]; ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <i class="fa fa-link" aria-hidden="true"></i> <?php echo $this->lang->line('empresa_departamento_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    <?php                                            
                        if(isset($arrDepartamento[0]) && count($arrDepartamento[0]) > 0)
                        {
                            $valor = $arrRespuesta[0]['empresa_departamento_codigo'];
                            
                            echo html_select('empresa_departamento', $arrDepartamento, 'lista_codigo', 'lista_valor', '', $valor);
                        }
                        else
                        {
                            echo html_select('empresa_departamento', $arrVacio, 'lista_codigo', 'lista_valor', 'SINSELECCIONAR', $valor);
                        }
                    ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <i class="fa fa-link" aria-hidden="true"></i> <?php echo $this->lang->line('empresa_municipio_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    <?php                                            
                        if(isset($arrCiudad[0]) && count($arrCiudad[0]) > 0)
                        {
                            $valor = $arrRespuesta[0]['empresa_municipio_codigo'];
                            
                            echo html_select('empresa_municipio', $arrCiudad, 'lista_codigo', 'lista_valor', '', $valor);
                        }
                        else
                        {
                            echo html_select('empresa_municipio', $arrVacio, 'lista_codigo', 'lista_valor', 'SINSELECCIONAR', $valor);
                        }
                    ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <i class="fa fa-link" aria-hidden="true"></i> <?php echo $this->lang->line('empresa_zona_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    <?php                                            
                        if(isset($arrZona[0]) && count($arrZona[0]) > 0)
                        {
                            $valor = $arrRespuesta[0]['empresa_zona_codigo'];
                            
                            echo html_select('empresa_zona', $arrZona, 'lista_codigo', 'lista_valor', '', $valor);
                        }
                        else
                        {
                            echo html_select('empresa_zona', $arrVacio, 'lista_codigo', 'lista_valor', 'SINSELECCIONAR', $valor);
                        }
                    ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_tipo_calle_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    <?php                                            
                        if(isset($arrTipoCalle[0]) && count($arrTipoCalle[0]) > 0)
                        {
                            $valor = $arrRespuesta[0]['empresa_tipo_calle_codigo'];
                            
                            echo html_select('empresa_tipo_calle', $arrTipoCalle, 'lista_codigo', 'lista_valor', '', $valor);
                        }
                        else
                        {
                            echo html_select('empresa_tipo_calle', $arrVacio, 'lista_codigo', 'lista_valor', 'SINSELECCIONAR', $valor);
                        }
                    ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_calle'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["empresa_calle"]; ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_numero'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["empresa_numero"]; ?>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_direccion_geo'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <br />
                    
                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_MapaEmpresa('<?php echo $arrRespuesta[0]['empresa_id']; ?>')">
                        <strong> <i class="fa fa-map-marker" aria-hidden="true"></i> Ver/Registrar Geolocalización </strong>
                    </span>
                    
                    <?php
                    
                    if($arrRespuesta[0]['empresa_direccion_geo'] == '')
                    {
                        echo '<i class="fa fa-ban" aria-hidden="true"></i> Debe registrar la geolocalización';
                    }
                    
                    ?>
                    
                    <br /><br />
                    
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_direccion_literal'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["empresa_direccion_literal"]; ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_info_adicional'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["empresa_info_adicional"]; ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('ejecutivo_asignado_nombre'); ?> para Mantenimientos
                </td>

                <td style="width: 70%;">
                    
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Importante: Al cambiar al Usuario App sólo se cambiará la asignación de la Empresa para Mantenimientos. 
                    <br />El Prospecto asociado a la Empresa continuará con el Usuario App actual, por lo que cambiarlo generará que no pueda editar la información de la Empresa. Actualice el Usuario App cuando la empresa esté afiliada.
                    
                    <?php echo $arrEjecutivo; ?>
                </td>
            </tr>

        </table>

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Afiliador/Prospecto');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
        

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "> ¿Verificó que toda la información sea la correcta? La información de la Empresa que será actualizada podría generar conflicto en instancias que realizaron una evaluación previa al cambio. </div>
        
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