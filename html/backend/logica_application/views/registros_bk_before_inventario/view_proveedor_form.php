<script type="text/javascript">
    
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", '../Proveedor/Guardar',
            'Lista_Proveedor', 'divErrorListaResultado');
    
    $(document).ready(function() {
        $("#proveedor_frecuencia_dias").togglebutton();
        $("div.modal-backdrop").remove();
    });
    
    $("#divOpciones").show();    
    $("#confirmacion").hide();

    function MostrarConfirmacion()
    {
        $("#divOpciones").hide();
        $("#confirmacion").fadeIn(500);
    }
    
    function OcultarConfirmacion()
    {
        $("#divOpciones").fadeIn(500);
        $("#confirmacion").hide();
    }
    
    function EliminarProveedor(estructura_id, proveedor_id) {
        var strParametros = "&estructura_id=" + estructura_id + "&proveedor_id=" + proveedor_id;
        Ajax_CargadoGeneralPagina('../Proveedor/Eliminar', 'Lista_Proveedor', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
    
</script>

<div class="container" style="overflow-y: auto; overflow-x: hidden; height: 375px;">
    
    <form id="FormularioRegistroLista" method="post" style="margin-bottom: 80px;">
    
        <input type="hidden" name="estructura_id" value="<?php if(isset($estructura_id)){ echo $estructura_id; } ?>" />
        <input type="hidden" name="proveedor_id" value="<?php if(isset($proveedor_id)){ echo $proveedor_id; } ?>" />
        
        <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='proveedor_nombre'>Datos del proveedor:</label><?php echo $arrCajasHTML['proveedor_nombre']; ?></div></div>
        <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='proveedor_lugar_compra'>Lugar de compra:</label><?php echo $arrCajasHTML['proveedor_lugar_compra']; ?><br /><label class='label-campo' for=''>Frecuencia en Días</label><br /><?php echo $arrCajasHTML['proveedor_frecuencia_dias']; ?></div></div>
        
        
        <div class='col-sm-6'>

            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='proveedor_importe'>Importe</label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['proveedor_importe']; ?>
                    </div>

                </div>
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='proveedor_fecha_ultima'>F. Última Compra</label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['proveedor_fecha_ultima']; ?>
                    </div>

                </div>
            </div>

        </div>
        
        <div class='col-sm-12'><div class='form-group'><label class='label-campo' for='proveedor_aclaracion'><?php echo $this->lang->line('proveedor_aclaracion'); ?>:</label><?php echo $arrCajasHTML['proveedor_aclaracion']; ?></div></div>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <br /><br />
        
        
        
        <div class='col-sm-12' id="divOpciones">

            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <?php
                    
                    if($proveedor_id != -1)
                    {
                        echo '
                            
                            <div class="col" style="text-align: center;">
                                <span class="nav-borrar" id="" onclick="MostrarConfirmacion();"> ELIMINAR </span>
                            </div>
                            
                            ';
                    }
                    
                    ?>
                    
                    <div class="col" style="text-align: center;">
                        <span class="nav-avanza" id="btnGuardarDatosLista"> GUARDAR </span>
                    </div>

                </div>
                
            </div>
            
        </div>
        
        <div class='col-sm-12' id="confirmacion" style="text-align: center;">
            
            <span class="texto-borrar">¿Confirma la acción de Eliminar?</span><br /><br />
            
            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: center;">
                        <span class="nav-borrar" id="" onclick="OcultarConfirmacion();">Cancelar </span>
                    </div>
                    
                    <div class="col" style="text-align: center;">
                        <span class="nav-borrar" onclick="EliminarProveedor('<?php if(isset($arrRespuesta[0]['prospecto_id'])) { echo $arrRespuesta[0]['prospecto_id']; } ?>', '<?php if(isset($arrRespuesta[0]['proveedor_id'])) { echo $arrRespuesta[0]['proveedor_id']; } ?>');"> ELIMINAR </span>
                    </div>

                </div>
                
            </div>
            
        </div>
        
    </form>
        
</div>