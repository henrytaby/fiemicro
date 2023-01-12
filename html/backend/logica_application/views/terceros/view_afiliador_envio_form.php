<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Afiliador/Registro/Guardar',
            'divVistaMenuPantalla', 'divErrorListaResultado');

    function RemitirCorreo(codigo) {
        
        var cnfrm = confirm('¿Está seguro de remitir el Correo?');
        if(cnfrm != true)
        {
            return false;
        }
        else
        {
            var strParametros = '&codigo=' + codigo + '&afiliador_texto_custom=' + $('#afiliador_texto_custom').val();
            Ajax_CargadoGeneralPagina('Afiliador/Envio/Guardar', 'divElementoFlotante', 'divErrorBusqueda', '', strParametros);
        }
    }
    
    document.getElementById("divContenidoElementoFlotante").style.top = "40px";

</script>

<div id="FormularioVentanaAuxiliar" style="overflow-y: auto; height: 500px;">

    <br /><br />
    
        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('AfiliadorTitulo_enviar'); ?></div>
        <div class="FormularioSubtituloComentarioNormal" style="width: 100% !important;"><?php echo $this->lang->line('AfiliadorSubtitulo_enviar'); ?></div>

    <div style="clear: both"></div>

    <br />

    <form id="FormularioRegistroLista" method="post">

        <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

        <input type="hidden" name="redireccionar" value="" />

        <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["afiliador_id"])){ echo $arrRespuesta[0]["afiliador_id"]; } ?>" />

        <input type="hidden" name="tipo_accion" value="<?php echo $tipo_accion; ?>" />

    <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

        <?php $strClase = "FilaBlanca"; ?>

        <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
        <tr class="FilaGris">

            <td colspan="2" style="width: 100%;">
                <?php echo $contenido_mensaje; ?>
            </td>

        </tr>

        <tr class="FilaBlanca">

            <td style="width: 25%; font-weight: bold;">
                <?php echo $this->lang->line('afiliador_texto_custom'); ?>
            </td>
            
            <td style="width: 75%; font-weight: bold;">
                <?php echo $arrCajasHTML["afiliador_texto_custom"]; ?>
            </td>

        </tr>

    </table>

    </form>

    <br /><br /><br />
    
    <div style="text-align: center;">
        <a onclick="RemitirCorreo('<?php if(isset($arrRespuesta[0]["afiliador_id"])){ echo $arrRespuesta[0]["afiliador_id"]; } ?>')" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
    </div>

    <div style="clear: both"></div>



</div>