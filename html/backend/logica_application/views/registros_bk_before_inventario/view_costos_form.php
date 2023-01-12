<script type="text/javascript">
    
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", '../Costos/Guardar',
            'ResultadoDetalleProducto', 'divErrorListaResultado');
    
    $(document).ready(function() {
        $("#detalle_costo_medida_convertir").togglebutton();
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
    
    $("#detalle_costo_medida_convertir").on('change', function(){
            
        var valor = $('#detalle_costo_medida_convertir option:selected').val();
        conversion_opcion(valor);
    });
    
    function conversion_opcion(valor)
    {
        switch (valor) {
            case "0": $(".conversion_medida").hide(); break;
            case "1": $(".conversion_medida").fadeIn(); break;

            default: $(".conversion_medida").hide(); break;
        }
    }
    
    <?php echo 'conversion_opcion("' . $conversion_opcion . '");'; ?>
    
    function EliminarCostos(estructura_id, codigo_detalle) {
        var strParametros = "&estructura_id=" + estructura_id + "&codigo_detalle=" + codigo_detalle;
        Ajax_CargadoGeneralPagina('../Costos/Eliminar', 'ResultadoDetalleProducto', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
    
</script>

<div class="container" style="overflow-y: auto; overflow-x: hidden; height: 375px;">
    
    <form id="FormularioRegistroLista" method="post" style="margin-bottom: 80px;">
    
        <input type="hidden" name="estructura_id" value="<?php if(isset($estructura_id)){ echo $estructura_id; } ?>" />
        <input type="hidden" name="codigo_detalle" value="<?php if(isset($codigo_detalle)){ echo $codigo_detalle; } ?>" />
        
        <div class='col-sm-6'><div class='form-group'><label class='label-campo label-campo_amplio' for='detalle_descripcion'><?php echo $this->lang->line('detalle_descripcion'); ?>:</label><?php echo $arrCajasHTML['detalle_descripcion']; ?></div></div>
        
        <div class='col-sm-6'>
            <div class="container">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo label-campo_amplio' for='detalle_cantidad'><?php echo $this->lang->line('detalle_cantidad'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['detalle_cantidad']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div class='col-sm-6'>
            <div class="container">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo label-campo_amplio' for='detalle_unidad'><?php echo $this->lang->line('detalle_unidad'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['detalle_unidad']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div class='col-sm-6 monto_costo_unitario'>
            <div class="container">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo label-campo_amplio' for='detalle_costo_unitario'><?php echo $this->lang->line('detalle_costo_unitario'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['detalle_costo_unitario']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div class='col-sm-12'>
            <div class="container">
                <div class="row">

                    <div class="col" style="text-align: center;">
                        <label class='label-campo label-campo_amplio' style="text-align: center;" for='detalle_costo_medida_convertir'><?php echo $this->lang->line('detalle_costo_medida_convertir'); ?><br />(cambiará el Costo Unitario)</label>
                        <br />
                         <?php echo $arrCajasHTML['detalle_costo_medida_convertir']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div style="clear: both;"><br /></div>
        
        <div class='col-sm-6 conversion_medida'>
            <div class="container">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo label-campo_amplio' for=''><?php echo $this->lang->line('detalle_costo_medida_unidad'); ?> <span class="AyudaTooltip" data-balloon-length="small" data-balloon="Indicar ¿Qué es lo que se compra?" data-balloon-pos="right"> </span> </label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['detalle_costo_medida_unidad']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div class='col-sm-6 conversion_medida'>
            <div class="container">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo label-campo_amplio' for=''><?php echo $this->lang->line('detalle_costo_medida_precio'); ?> <span class="AyudaTooltip" data-balloon-length="small" data-balloon="Indicar ¿A cuánto se compra?" data-balloon-pos="right"> </span> </label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['detalle_costo_medida_precio']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div class='col-sm-6 conversion_medida'>
            <div class="container">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo label-campo_amplio' for=''><?php echo $this->lang->line('detalle_costo_unidad_medida_uso'); ?> <span class="AyudaTooltip" data-balloon-length="small" data-balloon="Indicar ¿Qué se vende?" data-balloon-pos="right"> </span> </label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['detalle_costo_unidad_medida_uso']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div class='col-sm-6 conversion_medida'>
            <div class="container">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo label-campo_amplio' for=''><?php echo $this->lang->line('detalle_costo_unidad_medida_cantidad'); ?> <span class="AyudaTooltip" data-balloon-length="small" data-balloon="Cuántas unidades vendemos, en base a las unidades que compramos. Ej: En una caja de gaseosas vendo 12 botellas." data-balloon-pos="right"> </span> </label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['detalle_costo_unidad_medida_cantidad']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <br /><br />
        
        <div class='col-sm-12' id="divOpciones">

            <div class="container">
                <div class="row">

                    <?php
                    
                    if($codigo_detalle != -1)
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
            
            <div class="container">
                <div class="row">

                    <div class="col" style="text-align: center;">
                        <span class="nav-borrar" id="" onclick="OcultarConfirmacion();">Cancelar </span>
                    </div>
                    
                    <div class="col" style="text-align: center;">
                        <span class="nav-borrar" onclick="EliminarCostos('<?php if(isset($arrRespuesta[0]['producto_id'])) { echo $arrRespuesta[0]['producto_id']; } ?>', '<?php if(isset($arrRespuesta[0]['detalle_id'])) { echo $arrRespuesta[0]['detalle_id']; } ?>');"> ELIMINAR </span>
                    </div>

                </div>
                
            </div>
            
        </div>
        
    
    </form>
        
</div>