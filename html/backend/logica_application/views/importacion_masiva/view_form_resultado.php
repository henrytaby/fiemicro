<script type="text/javascript">

<?php
// Si no existe informaci?n, no se mostrar? como tabla con columnas con ?rden
if(count($arrRespuesta[0]) > 0 && $aux == 0)
{
?>  
    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": 10,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[0, "asc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
    
    function Ajax_CargarAccion_DetalleUsuario(tipo_codigo, codigo_usuario) {
        var strParametros = "&tipo_codigo=" + tipo_codigo + "&codigo_usuario=" + codigo_usuario;
        Ajax_CargadoGeneralPagina('Usuario/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleCampana(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Campana/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
<?php
}
?>

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
    
    function Ajax_CargarAccion_GuardarMasivo() {
        var strParametros = '';
        Ajax_CargadoGeneralPagina('Importacion/Guardar', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('ImportacionResultadoTitulo') . $aux_text; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('ImportacionResultadoSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Importacion/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
        
        <?php
        
        if($aux == 0)
        {
        
        ?>
        
            <div class="Botones2Opciones">
                <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('import_guardar'); ?> </a>
            </div>
        
        <?php
        
        }
        
        ?>
        
        <div style="clear: both"></div>
        
        <br /><br /><br />
        
        <?php
        
        if($aux == 0)
        {
        
        ?>
        
            <div class="FormularioSubtituloMediano" style="float: left; margin-left: 4%;"> <?php echo "Se importarán " . count($arrRespuesta) . " registro(s). " . $this->lang->line('import_titulo_verificar'); ?></div>
        
            <div style="clear: both"></div>

            <br />
        
            <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
                    <thead>

                            <tr class="FilaCabecera">

                                    <th style="width:5%;">
                                       N°
                                    </th>

                                    <!-- Similar al EXCEL -->

                                    <th style="width:10%;">
                                       <?php echo $this->lang->line('import_campana'); ?>       </th>
                                    <th style="width:5%;">
                                       <?php echo $this->lang->line('import_idc'); ?>       </th>
                                    <th style="width:5%;">
                                       Código Cliente   </th>
                                    <th style="width:10%;">
                                       <?php echo $this->lang->line('import_nombre_cliente'); ?>   </th>
                                    <th style="width:10%;">
                                       <?php echo $this->lang->line('import_empresa'); ?>   </th>
                                    <th style="width:10%;">
                                       <?php echo $this->lang->line('import_ingreso'); ?>   </th>
                                    <th style="width:10%;">
                                       <?php echo $this->lang->line('import_direccion'); ?>   </th>
                                    <th style="width:5%;">
                                       <?php echo $this->lang->line('import_telefono'); ?>   </th>
                                    <th style="width:5%;">
                                       <?php echo $this->lang->line('import_celular'); ?>   </th>
                                    <th style="width:10%;">
                                       <?php echo $this->lang->line('import_correo'); ?>   </th>
                                    <th style="width:10%;">
                                       Mensaje   </th>
                                    <th style="width:5%;">
                                       <?php echo $this->lang->line('import_matricula'); ?>   </th>
                                    <th style="width:5%;">
                                       Estado   </th>

                            </tr>
                    </thead>
                    <tbody>
                            <?php

                            $mostrar = true;
                            if (count($arrRespuesta[0]) == 0) 
                            {
                                    $mostrar = false;
                            }

                            if ($mostrar) 
                            {                
                                    $i=0;
                                    $strClase = "FilaBlanca";
                                    foreach ($arrRespuesta as $key => $value) 
                                    {
                                            $i++;

                                            ?> 
                                            <tr class="<?php echo $strClase; ?>">

                                                    <td style="text-align: center;">
                                                            <?php echo $i; ?>
                                                    </td>

                                                    <!-- Similar al EXCEL -->

                                                    <td style="text-align: center;">
                                                        
                                                        <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleCampana('<?php echo $value["campana_id"]; ?>')">
                                                        
                                                            <?php echo $value["campana_nombre"]; ?>
                                                            
                                                        </span>
                                                            
                                                    </td>
                                                    <td style="text-align: center;">
                                                            <?php echo $value["idc"]; ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <?php echo $value["codigo_cliente"]; ?>
                                                    <td style="text-align: center;">
                                                        <?php echo $value["nombre_cliente"]; ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                            <?php echo $value["empresa"]; ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                            <?php echo $value["ingreso"]; ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                            <?php echo $value["direccion"]; ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                            <?php echo $value["telefono"]; ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                            <?php echo $value["celular"]; ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                            <?php echo $value["correo"]; ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                            <?php echo $value["mensaje"]; ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        
                                                        <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleUsuario(-1, '<?php echo $value["codigo_usuario"]; ?>')">
                                                        
                                                            <?php echo $value["matricula"]; ?>
                                                            
                                                        </span>
                                                        
                                                    </td>
                                                    
                                                    <td style="text-align: center;">
                                                            <?php echo $value["etapa"]; ?>
                                                    </td>

                                            </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                    <?php
                                    $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca";
                                    //endfor;
                            }
                            else 
                            {
                            ?>
                            <tr>
                                    <td style="width: 55%; color: #25728c;" colspan="<?php echo $cantidad_columnas; ?>">
                                            <br><br>
                                            <?php echo $this->lang->line('TablaNoRegistros'); ?>
                                            <br><br>                                           
                                    </td>                               
                            </tr>
                    <?php } ?>
            </table>

        <?php
        
        }
        
        if($aux == 1)
        {
        
        ?>
        
            <div class="FormularioSubtituloMediano" style="float: left; margin-left: 4%;"> <?php echo "Se encontró " . count($arrRespuesta) . " error(es)." . $this->lang->line('import_titulo_error'); ?></div>

            <div style="clear: both"></div>

            <br />
            
        <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
                    <thead>

                            <tr class="FilaCabecera">

                                    <th style="width:10%;"> Hoja </th>
                                
                                    <th style="width:10%;"> N° Fila </th>

                                    <!-- Similar al EXCEL -->

                                    <th style="width:20%;"> Campo </th>
                                    <th style="width:20%;"> Valor </th>
                                    <th style="width:40%;"> Mensaje </th>

                            </tr>
                    </thead>
                    <tbody>
                            <?php

                            $mostrar = true;
                            if (count($arrRespuesta[0]) == 0) 
                            {
                                    $mostrar = false;
                            }
                            
                            if ($mostrar) 
                            {
                                    $i=0;
                                    $strClase = "FilaBlanca";
                                    foreach ($arrRespuesta as $key => $value) 
                                    {
                                            ?> 
                                            <tr class="<?php echo $strClase; ?>">

                                                    <!-- Similar al EXCEL -->
                                                
                                                    <td style="text-align: center;">
                                                            <?php echo $value["hoja"]; ?>
                                                    </td>
                                                    
                                                    <td style="text-align: center;">
                                                            <?php echo $value["linea"]; ?>
                                                    </td>

                                                    <td style="text-align: center;">
                                                            <?php echo $value["campo"]; ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                            <?php echo $value["valor"]; ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                            <?php echo $value["mensaje"]; ?>
                                                    </td>
                                                    
                                                    <!-- Similar al EXCEL -->
                                            </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                    <?php
                                    $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca";
                                    //endfor;
                            }
                            ?>
                            
            </table>

        <?php
        
        }
        
        ?>
        
    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('aprobar_importar_Pregunta'); ?></div>
        
            <div style="clear: both"></div>
        
            <br />

        <div class="PreguntaConfirmar">
            <?php echo $this->lang->line('PreguntaContinuar'); ?>
        </div>

        <div class="Botones2Opciones">
            <a onclick="OcultarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarAccion_GuardarMasivo()" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
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