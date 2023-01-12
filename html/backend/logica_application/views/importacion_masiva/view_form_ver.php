<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");

    $(document).ready(function () {
        $('input[type=file]').change(function () {
            var val = $(this).val().toLowerCase();
            var regex = new RegExp("(.*?)\.(xls|xlsx)$");
            if(!(regex.test(val))) 
            {
                $(this).val('');                
                alert('Archivo no válido, por favor seleccione un documento XLS o XLSX.');
                
                $("#MensajeArchivo").fadeOut(150, function() {
                    $(this).addClass("ocultar_elemento");
                });
            }
            else
            {
                $("#MensajeArchivo").fadeIn(150, function() {
                    $(this).removeClass("ocultar_elemento");
                });
            }
        });    
    });
    
        $(document).ready(function () {

            $('#FormularioRegistroLista').on('submit', function (event) {
                    event.preventDefault();
                    $.ajax({
                            url: "<?php echo base_url(); ?>Importacion/Subir",
                            method: "POST",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                    $('#file-upload').val('');
                                    $("#MensajeArchivo").fadeOut(150, function() {
                                        $(this).addClass("ocultar_elemento");
                                    });
                                    
                                    if(data == '')
                                    {
                                         Ajax_CargadoGeneralPagina('Importacion/Resultado', 'divVistaMenuPantalla', "divErrorBusqueda", '', "&codigo=1");
                                    }
                                    else
                                    {
                                        alert(data);
                                    }
                            }
                    });
            });

        });

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('ImportacionFormTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('ImportacionFormSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <br />
        
		<form id="FormularioRegistroLista" method="post" enctype="multipart/form-data">
		
			<table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

				<?php $strClase = "FilaGris"; ?>

				<tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <?php echo $this->lang->line('ImportacionFormDoc'); ?>
                                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='<?php echo $this->lang->line('ImportacionFormDocAyuda'); ?>' data-balloon-pos="right"> </span>
                                    </td>

                                    <td style="width: 70%;">

                                        <label for="file-upload" class="custom-file-upload">
                                            <?php echo $this->lang->line('TablaOpciones_SubirExcel'); ?>
                                        </label>
                                        <input id="file-upload" type="file" name="documento_pdf" accept=".xls, .xlsx" />

                                        <span id="MensajeArchivo" class="ocultar_elemento">
                                            <?php echo $this->lang->line('acta_excepcion_pdf_ok'); ?>
                                        </span>

                                    </td>

                                </tr>
				
			</table>
                    
                    
                    <br />
                    <div class="Botones2Opciones" style="float: right;">

                         <a id="btnGuardarDatosLista" class="BotonMinimalista"> <?php echo $this->lang->line('import_verificar'); ?> </a>

                    </div>
		
		</form>
		
        <div style="clear: both"></div>
        
		<div id="divErrorListaResultado" class="mensajeBD"> </div>
		
        <div id="divResultadoReporte" style="width: 100%;"> </div>
        
        <br /><br /><br /><br /><br /><br />

        <div id="divErrorBusqueda" class="mensajeBD">  </div>
        
    </div>
    
</div>