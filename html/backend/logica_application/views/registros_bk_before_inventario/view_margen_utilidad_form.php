<script type="text/javascript">
    
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", '../Margen/Guardar',
            'Margen_Utilidad', 'divErrorListaResultado');
    
    $(document).ready(function() {
        $("#producto_frecuencia").togglebutton();
        $("#producto_categoria_mercaderia").togglebutton();
        $("#producto_seleccion").togglebutton();
        $("#producto_opcion").togglebutton();
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
    
    function EliminarProducto(estructura_id, producto_id, tab) {
        var strParametros = "&estructura_id=" + estructura_id + "&producto_id=" + producto_id + "&tab=" + tab;
        Ajax_CargadoGeneralPagina('../Margen/Eliminar', 'Margen_Utilidad', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
    
    $("#producto_opcion").on('change', function(){
            
        var valor = $('#producto_opcion option:selected').val();

        producto_opcion(valor);
    });
    
    function producto_opcion(valor)
    {
        $(".opcion_costeo").hide();
        $(".opcion_personalizado").hide();
        
        switch (valor) {
            case "2": $(".opcion_costeo").fadeIn(500); break;
            case "3": $(".opcion_personalizado").fadeIn(500); break;

            default: break;
        }
    }
    
    <?php
    
    if($tab == 1)
    {
        echo '$(".tab_inventario").hide();';
    }
    else
    {
        echo '$(".tab_margen").hide();';
    }
    
    echo 'producto_opcion("' . $producto_opcion . '");';
    
    ?>
    
</script>

<div class="container" style="overflow-y: auto; overflow-x: hidden; height: 375px;">
    
    <form id="FormularioRegistroLista" method="post" style="margin-bottom: 80px;">
    
        <input type="hidden" name="estructura_id" value="<?php if(isset($estructura_id)){ echo $estructura_id; } ?>" />
        <input type="hidden" name="producto_id" value="<?php if(isset($producto_id)){ echo $producto_id; } ?>" />
        <input type="hidden" name="tab" value="<?php if(isset($tab)){ echo $tab; } ?>" />
        
        <div class='col-sm-12 tab_inventario'><div class='form-group'><label class='label-campo' for='producto_nombre'>Descripción del Producto:</label><?php echo $arrCajasHTML['producto_nombre']; ?></div></div>
        
        <div class='col-sm-12 tab_margen'><div class='form-group'><label class='label-campo' for='producto_nombre'>Descripción del Producto:</label><br /><label class='label-campo texto-centrado'><?php if(isset($arrRespuesta[0]['producto_nombre'])) { echo $arrRespuesta[0]['producto_nombre']; } ?></label></div></div>
        
        <div class='col-sm-6 tab_margen'><div class='form-group'><label class='label-campo' for=''>Frecuencia en Días</label><br /><?php echo $arrCajasHTML['producto_frecuencia']; ?></div></div>

        <div class='col-sm-6'>
            
            <div class="container tab_inventario" style="margin-top: 5px;">
                
                <div class="row">
                    <div class="col" style="text-align: center;">
                        <label class='label-campo' for='producto_compra_cantidad'><?php echo $this->lang->line('producto_compra_cantidad'); ?> </label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <?php echo $arrCajasHTML['producto_compra_cantidad']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: center;">
                        <label class='label-campo' for='producto_compra_medida'><?php echo $this->lang->line('producto_compra_medida'); ?></label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <?php echo $arrCajasHTML['producto_compra_medida']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: center;">
                        <label class='label-campo' for='producto_compra_precio'><?php echo $this->lang->line('producto_compra_precio'); ?></label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <?php echo $arrCajasHTML['producto_compra_precio']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: center;">
                        <label class='label-campo' for='producto_unidad_venta_unidad_compra'><?php echo $this->lang->line('producto_unidad_venta_unidad_compra'); ?> <span class="AyudaTooltip" data-balloon-length="small" data-balloon="En caso que se venda a la misma unidad que se compra, colocar valor 1, caso contrario, la cantidad correspondiente." data-balloon-pos="right"> </span> </label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <?php echo $arrCajasHTML['producto_unidad_venta_unidad_compra']; ?>
                    </div>
                </div>
                
                <div class="row">

                    <div class="col" style="text-align: center;">
                        <label class='label-campo' for='producto_venta_medida'>U. medida venta</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <?php echo $arrCajasHTML['producto_venta_medida']; ?>
                    </div>

                </div>
                
            </div>
            
            <div class="container tab_margen" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: center;">
                        <label class='label-campo' for='producto_venta_cantidad'><?php echo $this->lang->line('inventario_venta_cantidad'); ?></label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <?php echo $arrCajasHTML['producto_venta_cantidad']; ?>
                    </div>

                </div>
                <div class="row">

                    <div class="col" style="text-align: center;">
                        <label class='label-campo' for='producto_venta_precio'>Precio Venta Bs.:</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <?php echo $arrCajasHTML['producto_venta_precio']; ?>
                    </div>

                </div>
            </div>

        </div>
        
        <div class='col-sm-6 tab_inventario'><div class='form-group'><label class='label-campo' for='producto_aclaracion'>Aclaración:</label><?php echo $arrCajasHTML['producto_aclaracion']; ?></div></div>
        
        <div class='col-sm-6'>
            
            <div class="container tab_inventario" style="margin-top: 5px;">
                
                <div class="row">
                    <div class="col" style="text-align: center;">
                        <label class='label-campo' for='producto_categoria_mercaderia'><?php echo $this->lang->line('producto_categoria_mercaderia'); ?></label>
                        <br />
                        <?php echo $arrCajasHTML['producto_categoria_mercaderia']; ?>
                        <br /><br />
                    </div>
                </div>
                
                <div class="row">
                    <div class="col" style="text-align: center;">
                        <label class='label-campo' for='producto_opcion' style="text-align: center;"><?php echo $this->lang->line('producto_opcion'); ?></label>
                        <br />
                        <?php echo $arrCajasHTML['producto_opcion']; ?>
                        <br /><br />
                    </div>
                </div>
                
                <div class="row opcion_costeo">
                    <div class="col" style="text-align: center;">
                        <label class='label-campo' for='producto_costo_medida_unidad'><?php echo $this->lang->line('producto_costo_medida_unidad'); ?></label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <?php echo $arrCajasHTML['producto_costo_medida_unidad']; ?>
                    </div>
                </div>
                
                <div class="row opcion_costeo">
                    <div class="col" style="text-align: center;">
                        <label class='label-campo' for='producto_costo_medida_cantidad'><?php echo $this->lang->line('producto_costo_medida_cantidad'); ?></label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <?php echo $arrCajasHTML['producto_costo_medida_cantidad']; ?>
                    </div>
                </div>
                
                <div class="row opcion_costeo">
                    <div class="col" style="text-align: center;">
                        <label class='label-campo' for='producto_costo_medida_precio'><?php echo $this->lang->line('producto_costo_medida_precio'); ?></label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <?php echo $arrCajasHTML['producto_costo_medida_precio']; ?>
                    </div>
                </div>
                
                <div class="row opcion_personalizado">

                    <div class="col" style="text-align: center;">
                        <label class='label-campo' for='producto_venta_costo'>Costo Unitario:</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <?php echo $arrCajasHTML['producto_venta_costo']; ?>
                    </div>

                </div>
                
            </div>
            
        </div>
        
        <br />
        
        <div class='col-sm-12'>
            
            <div class="row tab_inventario">
                <div class="col" style="text-align: center;">
                    <label class='label-campo' for='producto_seleccion' style="text-align: center;"><?php echo $this->lang->line('producto_seleccion'); ?></label>
                    <br />
                    <?php echo $arrCajasHTML['producto_seleccion']; ?>
                    <br /><br />
                </div>
            </div>
            
        </div>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <br /><br />
        
        
        
        <div class='col-sm-12' id="divOpciones">

            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <?php
                    
                    if($producto_id != -1 && $tab == 2)
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
                        <span class="nav-borrar" onclick="EliminarProducto('<?php if(isset($arrRespuesta[0]['prospecto_id'])) { echo $arrRespuesta[0]['prospecto_id']; } ?>', '<?php if(isset($arrRespuesta[0]['producto_id'])) { echo $arrRespuesta[0]['producto_id']; } ?>', '<?php if(isset($tab)){ echo $tab; } ?>');"> ELIMINAR </span>
                    </div>

                </div>
                
            </div>
            
        </div>
        
    
    </form>
        
</div>