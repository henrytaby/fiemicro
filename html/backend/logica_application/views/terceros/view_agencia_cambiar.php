<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Afiliador/Cambiar/Guardar',
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
    
    function CapturaCodigoAgencia(codigo){
        document.getElementById('codigo_agencia_fie').value = codigo;
        $(".texto_agencia").html("<i class='fa fa-thumbs-o-up' aria-hidden='true'></i> Agencia Seleccionada");
        $(".texto_agencia").fadeIn();    
    }
    
    function VerTablaMapa(id) {
        $("#"+id).slideToggle();
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo '¡Transferir Registro a otra Agencia!'; ?></div>
            <div class="FormularioSubtituloComentarioNormal ">Con esta acción procederá a transferir el registro a otra Agencia, es decir que, cambiará la Agencia asociada al registro a la que seleccione. Considere que si usted transfiere el registro a una Agencia que no supervisa, ya no podrá verla. </div>
  
        <div style="clear: both"></div>
        
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["terceros_id"])){ echo $arrRespuesta[0]["terceros_id"]; } ?>" />
            

            
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    Nombre del Solicitante
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["nombre_persona"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    Correo Electrónico
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["email"]; ?>
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
                    <?php echo $this->lang->line('tipo_cuenta'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["tipo_cuenta"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('envio'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["envio"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('codigo_agencia_fie'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["codigo_agencia_fie"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <i class='fa fa-angle-double-right' aria-hidden='true'></i> Seleccione la Agencia a Transferir
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Se listan las Agencias registradas en el Sistema. Puede seleccionar la Agencia de forma manual o seleccionando la más cercana al Cliente en el Mapa de Agencias; el mapa es centreado respecto a la ubicación indicada por el cliente para el envío de documentos." data-balloon-pos="right"> </span>
                    
                    <br />
                    
                    <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaMapa('VisorMapaAgencias');">
                        <i class="fa fa-map-marker" aria-hidden="true"></i> Ver/Ocultar Mapa de Agencias
                    </div>
                    
                </td>

                <td style="width: 70%;">                
                    <?php
                    if(isset($arrAgencias[0]) && count($arrAgencias[0]) > 0)
                    {
                        
                        //
                        
                        $i = 0;

                        foreach ($arrAgencias as $key => $value) {

                            $arrAgencias[$i]['estructura_regional_nombre'] = $this->mfunciones_generales->GetValorCatalogoDB($arrAgencias[$i]['estructura_regional_ciudad'], 'dir_localidad_ciudad') . ' - ' . $arrAgencias[$i]['estructura_regional_nombre'];

                            $i++;
                        }
                        
                        //
                        
                        $codigo_agencia_fie = "";

                        if(isset($arrRespuesta[0]["codigo_agencia_fie_codigo"]))
                        { 
                            $codigo_agencia_fie = $arrRespuesta[0]["codigo_agencia_fie_codigo"];
                        }

                        echo html_select('codigo_agencia_fie', $arrAgencias, 'estructura_regional_id', 'estructura_regional_nombre', '', $codigo_agencia_fie);
                    }
                    else
                    {
                        echo $this->lang->line('TablaNoRegistros');
                    }
                    ?>
                    
                    <br />
                    
                    <span style="display: none;" class="texto_agencia"> </span>
                    
                </td>

            </tr>
            
        </table>

        <br />
            
        <div id="VisorMapaAgencias" style="text-align: center; width: 80%; display: block;">
                    
            <?php $url_armado = $this->mfunciones_generales->setEnlaceRegistro($arrRespuesta[0]["terceros_id"], 3, ''); ?>

            <iframe frameborder="0" scrolling="no" style="overflow-y: hidden; width: 100%; background-color: #ffffff; height: 465px;" src="<?php echo (site_url('Agencia/Mapa/?' . $url_armado));?>"> </iframe>

            <span style="display: none;" class="texto_agencia"> </span>
        </div>
            
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
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('cambiar_agencia_Pregunta'); ?></div>
        
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