<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Regional/Guardar',
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
    
    $("#estructura_regional_departamento").change(function(){
        var parent_codigo = $(this).val();
        $.ajax({
            url: 'Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_provincia', parent_tipo:'dir_departamento'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#estructura_regional_provincia").empty();
                $("#estructura_regional_provincia").append("<option value='-1'>-- Seleccione la Provincia --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#estructura_regional_provincia").append("<option value='"+id+"'>"+name+"</option>");
                }
                $("#estructura_regional_ciudad").empty();
                $("#estructura_regional_ciudad").append("<option value='-1'>-- Antes seleccione la Provincia --</option>");
            }
        });
    });
    
    $("#estructura_regional_provincia").change(function(){
        var parent_codigo = $(this).val();
        $.ajax({
            url: 'Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_localidad_ciudad', parent_tipo:'dir_provincia'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#estructura_regional_ciudad").empty();
                $("#estructura_regional_ciudad").append("<option value='-1'>-- Seleccione la Localidad/ciudad --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#estructura_regional_ciudad").append("<option value='"+id+"'>"+name+"</option>");
                }
            }
        });
    });
    
    $('input[type=file]').change(function () {
        
        var id_element = $(this).attr('id');

        var val = $(this).val().toLowerCase();
        var regex = new RegExp("(.*?)\.(jpg|png|jpeg)$");
        if(!(regex.test(val))) 
        {
          $(this).val('');
          document.getElementById("archivo_"+id_element).value = "";
          alert('Archivo no válido, por favor seleccione un documento JPG o PNG.');
        }
        else if((this.files[0].size/1024/1024) > 5){ // Size in MB
          alert("El archivo no puede superar los 5MB");
          this.value = "";
          document.getElementById("archivo_"+id_element).value = "";
        }
        else
        {
            document.getElementById("texto_firma").innerHTML = "Cargando imagen espere...";
            
          //Read File
          var selectedFile = document.getElementById(id_element).files;
          //Check File is not Empty
          if (selectedFile.length > 0) {
            // Select the very first file from list
            var fileToLoad = selectedFile[0];
            // FileReader function for read the file.
            var fileReader = new FileReader();
            var base64;
            // Onload of file read the file content
            fileReader.onload = function(fileLoadedEvent) {
              base64 = fileLoadedEvent.target.result;
              
              document.getElementById("archivo_"+id_element).value  = base64;
              
              document.getElementById("texto_firma").innerHTML = "<i class='fa fa-thumbs-o-up' aria-hidden='true'></i> Preparado para subir";
            };
            // Convert data to base64
            fileReader.readAsDataURL(fileToLoad);
          }
        }

    });
    
</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('RegionalTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('RegionalSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["estructura_id"])){ echo $arrRespuesta[0]["estructura_id"]; } ?>" />

            <input type="hidden" name="tipo_accion" value="<?php echo $tipo_accion; ?>" />
            
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

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
                    <?php echo $this->lang->line('estructura_parent'); ?> <?php echo $this->lang->line('estructura_entidad'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php                                            
                        if(count($arrParent[0]) > 0)
                        {
                            $valor_parent = '-1';
                            if($tipo_accion == 1)
                            {
                                $valor_parent = $arrRespuesta[0]['parent_id'];
                            }
                            
                            echo html_select('catalogo_parent', $arrParent, 'estructura_entidad_id', 'estructura_entidad_nombre', '', $valor_parent);
                        }
                        else
                        {
                            echo $this->lang->line('TablaNoRegistros');
                        }
                    ?>
                </td>
                
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('estructura_regional_direccion'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["estructura_regional_direccion"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('estructura_regional_departamento'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('estructura_regional_departamento', ($tipo_accion==1 ? $arrRespuesta[0]['estructura_regional_departamento'] : ''), 'dir_departamento'); ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('estructura_regional_provincia'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('estructura_regional_provincia', ($tipo_accion==1 ? $arrRespuesta[0]['estructura_regional_provincia'] : ''), 'dir_provincia', ($tipo_accion==1 ? $arrRespuesta[0]['estructura_regional_departamento'] : ''), 'dir_departamento'); ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('estructura_regional_ciudad'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('estructura_regional_ciudad', ($tipo_accion==1 ? $arrRespuesta[0]['estructura_regional_ciudad'] : ''), 'dir_localidad_ciudad', ($tipo_accion==1 ? $arrRespuesta[0]['estructura_regional_provincia'] : ''), 'dir_provincia'); ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('estructura_regional_geo'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["estructura_regional_geo"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('estructura_regional_estado'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["estructura_regional_estado"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('estructura_regional_responsable'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["estructura_regional_responsable"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td valign="top" style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('estructura_regional_firma'); ?>                    
                </td>

                <td valign="top" style="width: 70%;">
                    
                    <label for="file1" class="custom-file-upload"> Cargar Imagen Firma </label>
                    
                    <input type="file" name="documento1" id="file1" accept="image/x-png,image/jpeg">
                    <input type="hidden" id="archivo_file1" name="estructura_regional_firma" value="<?php echo (isset($arrRespuesta[0]["estructura_regional_firma"]) ? $arrRespuesta[0]["estructura_regional_firma"] : ''); ?>" />
                    
                    <?php
                    
                    if($tipo_accion == 1)
                    {
                        $imagen_tipo = substr($arrRespuesta[0]["estructura_regional_firma"], 5, strpos($arrRespuesta[0]["estructura_regional_firma"], ';')-5);

                        if(preg_match('(image/jpg|image/jpeg|image/png)', $imagen_tipo) === 1)
                        {
                            echo '<span id="texto_firma"> <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Imagen registrada </span> <br /><br />';

                            echo '<div style="text-align: center;"><img src="'. $arrRespuesta[0]["estructura_regional_firma"] . '" alt="Firma" style="max-width: 80%;" /></div>';
                        }
                        else
                        {
                            echo '<span id="texto_firma"> Sin imagen </span>';
                        }
                    }
                    else
                    {
                        echo '<span id="texto_firma"> </span>';
                    }
                    
                    ?>
                    
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('estructura_regional_monto'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["estructura_regional_monto"]; ?>
                </td>

            </tr>
            
        </table>

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Regional/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
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