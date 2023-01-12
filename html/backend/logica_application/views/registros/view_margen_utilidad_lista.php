<div class="tab-content">

    <div id="tab_margen_utilidad" class="tab-pane fade in active">
        
        <?php

        $mostrar = false;

        if(count($arrRespuesta[0]) > 0)
        {
            $mostrar = true;
        ?>

            <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

                <tr class="FilaGris">

                    <td style="width: 10%; font-weight: bold; text-align: center;">
                        Opc.
                    </td>

                    <td style="width: 15%; font-weight: bold; text-align: center; font-style: italic;">
                        Descripcion de producto
                    </td>
                    
                    <td style="width: 15%; font-weight: bold; text-align: center;">
                        Frecuencia en dias
                    </td>
                    
                    <td style="width: 15%; font-weight: bold; text-align: center;">
                        Cantidad vendida
                    </td>
                    
                    <td style="width: 15%; font-weight: bold; text-align: center; font-style: italic;">
                        Unidad de medidad de venta
                    </td>

                    <td colspan="2" style="width: 15%; font-weight: bold; text-align: center; font-style: italic; text-decoration: underline;">
                        <?php echo ($rubro==1||$rubro==2 ? 'Costo Unitario Bs.' : 'Precio de Compra'); ?>
                    </td>

                    <td style="width: 15%; font-weight: bold; text-align: center;">
                        Precio venta Bs.
                    </td>

                </tr>

                <?php

                if ($mostrar) 
                {
                    $suma_frecuencia = 0;

                    $suma_costo = 0;
                    $suma_precio = 0;
                    $strClase = "FilaBlanca";
                    foreach ($arrRespuesta as $key => $value) 
                    {
                        $suma_frecuencia = $suma_frecuencia + $this->mfunciones_generales->CalculoFecuencia($value["producto_venta_cantidad"], $value["producto_venta_precio"], $value["producto_frecuencia_valor"]);

                        $suma_costo = $suma_costo + $value["producto_venta_costo"];
                        $suma_precio = $suma_precio + $value["producto_venta_precio"];
                ?>
                        <tr class="FilaBlanca">

                            <td style="width: 10%; font-weight: bold; text-align: center;">
                                <span onclick="Margen_Nuevo(<?php echo $value["prospecto_id"]; ?>, <?php echo $value["producto_id"]; ?>, '1');" class="EnlaceSimple label-campo">
                                    <strong> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </strong>
                                </span>
                                
                                <?php
                                
                                if($this->mfunciones_generales->GetValorCatalogo($value["producto_opcion"], 'producto_opcion') == 'C')
                                {
                                    echo '
                                    
                                    <span class="label-campo">
                                        <strong> <i class="fa fa-arrows-h" aria-hidden="true"></i> </strong>
                                    </span>

                                    <span class="EnlaceSimple label-campo" onclick="DetalleProducto(' . $value["producto_id"] . ');" title="Registre el detalle de Costeo (Hoja de Costos)">
                                        <strong> <i class="fa fa-cubes" aria-hidden="true"></i> </strong>
                                    </span>';
                                }
                                
                                ?>
                                
                            </td>
                            
                            <td style="width: 15%; font-weight: normal; text-align: center; font-style: italic;">
                                <?php echo $value["producto_nombre"]; ?>
                            </td>

                            <td style="width: 15%; font-weight: normal; text-align: center; font-weight: bold; ">
                                <?php echo $value["producto_frecuencia"]; ?>
                            </td>
                            
                            <td style="width: 15%; font-weight: normal; text-align: center; font-weight: bold; ">
                                <?php echo number_format($value["producto_venta_cantidad"], 2, ',', '.'); ?>
                            </td>
                            
                            <td style="width: 15%; font-weight: normal; text-align: center; font-style: italic;">
                                <?php echo $value["producto_venta_medida"]; ?>
                            </td>

                            <td style="width: 14%; font-weight: normal; text-align: center; font-weight: bold;">
                                <?php echo number_format($value["producto_venta_costo"], 2, ',', '.'); ?>
                            </td>
                            
                            <td style="width: 1%; font-weight: normal; text-align: center; font-weight: bold; font-style: italic;">
                                <?php 
                                
                                echo $this->mfunciones_generales->GetValorCatalogo($value["producto_opcion"], 'producto_opcion');
                                
                                ?>
                            </td>

                            <td style="width: 15%; font-weight: normal; text-align: center; font-weight: bold; ">
                                <?php echo number_format($value["producto_venta_precio"], 2, ',', '.'); ?>
                            </td>

                        </tr>

                <?php
                    }
                }
                ?>

            </table>

            <div style="color: #666666; font-style: italic; padding-left: 35px;">

                P: Valor Personalizado
                <?php echo ($rubro==1||$rubro==2 ? '<br />C: Costeo. Valor por Hoja de Costos' : ''); ?>
                
            </div>
        
            <input type="hidden" id="total_suma_frecuencia" value="<?php echo $suma_frecuencia; ?>" />

            <br />

        <?php

        }

        else
        {            
            echo "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> Aún No se Registró Información <br /> Puede crear desde AÑADIR REGISTRO </div>";

            echo '<input type="hidden" id="total_suma_frecuencia" value="0" />';
        }

        ?>

    </div>
    
    <div id="tab_inventario" class="tab-pane fade">
        
        <script type="text/javascript">
        
            $(document).ready(function() {
                $("#inventario_registro").togglebutton();
                
                <?php echo 'metodo_inventario();'; ?>
                
            });
        
            $("#inventario_registro").on('change', function(){
                metodo_inventario();
            });
        
            function metodo_inventario()
            {
                var valor = $('#inventario_registro option:selected').val();

                $("#inventario_respaldo").hide();
                $("#inventario_detalle").hide();
                $("#boton_nuevo").hide();
                $("#sin_seleccion_inventario").hide();

                switch (valor) {
                    case "0": $("#inventario_detalle").fadeIn(500); $("#boton_nuevo").fadeIn(500); break;
                    case "1": $("#inventario_respaldo").fadeIn(500); break;
                    default: $("#sin_seleccion_inventario").fadeIn(500); break;
                }
            }
        
        </script>
        
        <div style="text-align: center;"> <?php echo $arrCajasHTML['inventario_registro']; ?> </div>
        
        <div id="sin_seleccion_inventario" style="text-align: center;">
            <br />
            <label class='label-campo texto-centrado' for=''> Debe seleccionar un método de Registro del Inventario: Registro Detallado o Total con Respaldo </label>
        </div>
        
        <div id="inventario_respaldo">
            
            <br />
            
            <div class='col-sm-6'>
                
                <label class='label-campo' for='inventario_registro_total'>Por favor registre el total del inventario y seleccione "Guardar" (también se guardará si añade un nuevo registro). <i>Recuerde digitalizar el respaldo.</i></label>
                    
            </div>
            
            <div class='col-sm-6'>
            
                <div class="row">
                    <div class="col" style="text-align: center;">
                        <?php echo $arrCajasHTML['inventario_registro_total']; ?>
                    </div>
                    <div class="col" style="text-align: center; margin-top: 8px;">
                        <span class="nav-avanza" onclick="GuardarInventario();"> <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar </span>
                        <div id="mensaje_guardado" style="display: none;"> <br><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Guardado Correcto!</div>
                    </div>
                </div>
                
            </div>
            
            <br /><br />
            
        </div>
        
        <div id="inventario_detalle">
            
            <br />
            
            <div style="text-align: right; display: none;" id="boton_nuevo">

                <span class="EnlaceSimple label-campo" onclick="Margen_Nuevo('<?php echo $estructura_id; ?>', '-1', '2');">
                    <strong> <i class="fa fa-plus-square" aria-hidden="true"></i> AÑADIR REGISTRO A INVENTARIO </strong>
                </span>

                <br /><br />

            </div>
            
            <?php

            $mostrar = false;

            $suma_mercaderia = 0;
            $suma_mp = 0;
            $suma_pp = 0;
            $suma_pt = 0;
            $suma_costo_total = 0;

            if(count($arrRespuesta2[0]) > 0)
            {
                $mostrar = true;
            ?>

                <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

                    <tr class="FilaGris">

                        <td style="width: 5%; font-weight: bold; text-align: center;">
                            Opc.
                        </td>

                        <td style="width: 20%; font-weight: bold; text-align: center; font-style: italic;">
                            Descripcion de producto
                        </td>

                        <td style="width: 15%; font-weight: bold; text-align: center; font-style: italic;">
                            Cantidad Comprada
                        </td>
                        <td style="width: 15%; font-weight: bold; text-align: center; font-style: italic;">
                            Unidad de Medida de Compra
                        </td>
                        <td style="width: 15%; font-weight: bold; text-align: center; font-style: italic;">
                            Categoría Mercadería
                        </td>
                        <td style="width: 15%; font-weight: bold; text-align: center; font-style: italic;">
                            Precio de Compra Unitario
                        </td>
                        <td style="width: 15%; font-weight: bold; text-align: center; font-style: italic;">
                            Costo Total
                        </td>


                    </tr>

                    <?php

                    if ($mostrar) 
                    {
                        $strClase = "FilaBlanca";
                        foreach ($arrRespuesta2 as $key => $value) 
                        {
                            if($value["producto_seleccion"] == 0)
                            {
                                $clase_auxiliar = "FilaBlanca";
                            }
                            else
                            {
                                $clase_auxiliar = "FilaSeleccion";
                            }

                            $costo_total = $value["producto_compra_cantidad"]*$value["producto_compra_precio"];

                            $suma_costo_total += $costo_total;

                            switch ($value["producto_categoria_mercaderia"]) {
                                case 1:
                                    $suma_mp += $costo_total;
                                    break;
                                case 2:
                                    $suma_pp += $costo_total;
                                    break;
                                case 3:
                                    $suma_pt += $costo_total;
                                    break;
                                case 4:
                                    $suma_mercaderia += $costo_total;
                                    break;
                                default:

                                    break;
                            }

                    ?>
                            <tr class="<?php echo $clase_auxiliar; ?>">

                                <td style="width: 5%; font-weight: bold; text-align: center;">
                                    <span class="EnlaceSimple label-campo" onclick="Margen_Nuevo(<?php echo $value["prospecto_id"]; ?>, <?php echo $value["producto_id"]; ?>, '2');">
                                        <strong> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </strong>
                                    </span>
                                </td>

                                <td style="width: 20%; font-weight: normal; text-align: center;">
                                    <?php echo $value["producto_nombre"]; ?>
                                </td>

                                <td style="width: 15%; font-weight: normal; text-align: center;">
                                    <?php echo number_format($value["producto_compra_cantidad"], 2, ',', '.'); ?>
                                </td>
                                <td style="width: 15%; font-weight: normal; text-align: center;">
                                    <?php echo $value["producto_compra_medida"]; ?>
                                </td>
                                <td style="width: 15%; font-weight: normal; text-align: center;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($value["producto_categoria_mercaderia"], 'categoria_mercaderia'); ?>
                                </td>
                                <td style="width: 15%; font-weight: normal; text-align: center;">
                                    <?php echo number_format($value["producto_compra_precio"], 2, ',', '.'); ?>
                                </td>
                                <td style="width: 15%; font-weight: normal; text-align: center;">
                                    <?php echo number_format($costo_total, 2, ',', '.'); ?>
                                </td>


                            </tr>

                    <?php
                        }
                    }
                    ?>

                </table>

                <br />

            <?php

            }

            else
            {            
                echo "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> Aún No se Registró Información <br /> Puede crear desde AÑADIR REGISTRO </div>";
            }

            ?>

            <div class="row">

                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for=''>TOTAL</label>
                </div>
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado panel-heading' for=''><?php echo number_format($suma_costo_total, 2, ',', '.'); ?></label>
                </div>

            </div>

            <br />

            <div class="row">

                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado' for=''>MERCADERÍA</label>
                </div>
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado panel-heading' for=''><?php echo number_format($suma_mercaderia, 2, ',', '.'); ?></label>
                </div>

            </div>

            <?php
            if($rubro == 1 || $rubro == 2)
            {
            ?>

                <div class="row">

                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado' for=''>MP E INSUMOS</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado panel-heading' for=''><?php echo number_format($suma_mp, 2, ',', '.'); ?></label>
                    </div>

                </div>
            
            <?php
            }
            if($rubro == 1)
            {
            ?>
            
                <div class="row">

                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado' for=''>PROD. TERM.</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado panel-heading' for=''><?php echo number_format($suma_pt, 2, ',', '.'); ?></label>
                    </div>

                </div>
                <div class="row">

                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado' for=''>PROD. PROC.</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado panel-heading' for=''><?php echo number_format($suma_pp, 2, ',', '.'); ?></label>
                    </div>

                </div>

            <?php
            }
            ?>
            
        </div>
    
    </div>
            
</div>
            
<script type="text/javascript">
    
    $(function(){
        
        <?php
        
            if($tab == "2")
            {
                echo '$("#tab_inventario").addClass(" in active"); $("#tab_margen_utilidad").removeClass(" in active");';
            }
        ?>
        
        TotalSumaMargen();
        
    });
    
</script>