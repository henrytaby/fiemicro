<script type="text/javascript">
    
    function Ajax_CargarAccion_VerFlujo() {        
        var codigo_flujo = $('#codigo_flujo').val();
        var editar = $('input[name=editar]:checked').val();
        var strParametros = "&codigo_flujo=" + codigo_flujo + "&editar=" + editar;
        Ajax_CargadoGeneralPagina('Flujo/Ver/Detalle', 'ResultadoFlujo', "divErrorBusqueda", 'SLASH', strParametros);
    }
    
</script>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('FlujoTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('FlujoSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaGris">
                
                <td style="width: 40%;">                
                    <select id="codigo_flujo" name="codigo_flujo">
                        <option value="1" mitext="Estado de operación del Cliente">Estado de operación del Cliente</option>
                        <option value="3" mitext="Cierre del Cliente">Cierre del Cliente</option>
                        <option value="2" mitext="Acciones Específicas">Acciones Específicas</option>
                        <option value="5" mitext="Proceso Onboarding">Proceso Onboarding</option>
                        <option value="6" mitext="Proceso Onboarding">Proceso Solicitud de Crédito</option>
                        <option value="7" mitext="Proceso Onboarding">Proceso Normalizador/Cobrador</option>
                    </select>
                </td>
                
                <td style="width: 20%; font-weight: bold;">
                                        
                    <input id="editar1" name="editar" type="radio" class="" checked="checked" value="0" />
                    <label for="editar1" class=""><span></span>Visualizar</label>

                    <br />

                    <input id="editar2" name="editar" type="radio" class="" value="1" />
                    <label for="editar2" class=""><span></span>Editar</label>
                    
                </td>
                
                <td style="width: 40%; font-weight: bold; text-align: center;">
                    <a onclick="Ajax_CargarAccion_VerFlujo();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
                </td>

            </tr>
        </table>
        
        <br />
        
        <div id="ResultadoFlujo"> </div>
        
        <div id="divErrorBusqueda" class="mensajeBD"> </div>

    </div>
</div>