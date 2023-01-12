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

                    <td style="width: 15%; font-weight: bold; text-align: center; font-style: italic;">
                        Unidad de medidad de venta
                    </td>

                    <td colspan="2" style="width: 15%; font-weight: bold; text-align: center; font-style: italic; text-decoration: underline;">
                        Costo Unitario Bs.
                    </td>

                    <td style="width: 15%; font-weight: bold; text-align: center;">
                        Frecuencia en dias
                    </td>

                    <td style="width: 15%; font-weight: bold; text-align: center;">
                        Cantidad vendida
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

                            <td style="width: 10%; font-weight: bold; text-align: center;" onclick="Margen_Nuevo(<?php echo $value["prospecto_id"]; ?>, <?php echo $value["producto_id"]; ?>, '1');">
                                <span class="EnlaceSimple label-campo">
                                    <strong> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </strong>
                                </span>
                            </td>

                            <td style="width: 15%; font-weight: normal; text-align: center; font-style: italic;">
                                <?php echo $value["producto_nombre"]; ?>
                            </td>

                            <td style="width: 15%; font-weight: normal; text-align: center; font-style: italic;">
                                <?php echo $value["producto_venta_medida"]; ?>
                            </td>

                            <td style="width: 14%; font-weight: normal; text-align: center; font-style: italic; font-weight: bold;">
                                <?php echo number_format($value["producto_venta_costo"], 2, ',', '.'); ?>
                            </td>

                            <td style="width: 1%; font-weight: normal; text-align: center; font-weight: bold; font-style: italic;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($value["producto_opcion"], 'producto_opcion'); ?>
                            </td>
                            
                            <td style="width: 15%; font-weight: normal; text-align: center; font-weight: bold; ">
                                <?php echo $value["producto_frecuencia"]; ?>
                            </td>

                            <td style="width: 15%; font-weight: normal; text-align: center; font-weight: bold; ">
                                <?php echo number_format($value["producto_venta_cantidad"], 2, ',', '.'); ?>
                            </td>

                            <td style="width: 15%; font-weight: normal; text-align: center; font-weight: bold; ">
                                <?php echo number_format($value["producto_venta_precio"], 2, ',', '.'); ?>
                            </td>

                        </tr>

                <?php
                    }
                }
                ?>

                    <tr class="FilaGris">

                        <td colspan="6" style="font-weight: bold; text-align: center;">
                            SUMATORIA BS.
                        </td>
                        <td style="font-weight: bold; text-align: center;">
                            <?php echo number_format($suma_precio, 2, ',', '.'); ?>
                        </td>

                    </tr>

            </table>

            <input type="hidden" id="total_suma_frecuencia" value="<?php echo $suma_frecuencia; ?>" />

            <br />

        <?php

        }

        else
        {            
            echo "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> Aún No se Registró Información <br /> Puede crear desde INVENTARIO y NUEVO REGISTRO </div>";

            echo '<input type="hidden" id="total_suma_frecuencia" value="0" />';
        }

        ?>

    </div>
    
    <div id="tab_inventario" class="tab-pane fade">
        
        <?php

        $mostrar = false;

        if(count($arrRespuesta2[0]) > 0)
        {
            $mostrar = true;
        ?>

            <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

                <tr class="FilaGris">

                    <td style="width: 5%; font-weight: bold; text-align: center;">
                        Opc.
                    </td>

                    <td style="width: 10%; font-weight: bold; text-align: center; font-style: italic;">
                        Descripcion de producto
                    </td>

                    <td style="width: 10%; font-weight: bold; text-align: center; font-style: italic;">
                        Unidad de medidad de venta
                    </td>

                    <td colspan="2" style="width: 10%; font-weight: bold; text-align: center; font-style: italic; text-decoration: underline;">
                        Costo Unitario Bs.
                    </td>
                    
                    <td style="width: 15%; font-weight: bold; text-align: center; font-style: italic;">
                        Aclaración
                    </td>
                    
                    <td style="width: 10%; font-weight: bold; text-align: center; font-style: italic;">
                        Cantidad Comprada
                    </td>
                    <td style="width: 10%; font-weight: bold; text-align: center; font-style: italic;">
                        Unidad de Medida de Compra
                    </td>
                    <td style="width: 10%; font-weight: bold; text-align: center; font-style: italic;">
                        Precio de Compra de la Unidad de Compra Bs.
                    </td>
                    <td style="width: 10%; font-weight: bold; text-align: center; font-style: italic;">
                        Cuantas unidades de Venta nos da una unidad de Compra
                    </td>
                    <td style="width: 10%; font-weight: bold; text-align: center; font-style: italic;">
                        Categoría Mercadería
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
                ?>
                        <tr class="<?php echo $clase_auxiliar; ?>">

                            <td style="width: 5%; font-weight: bold; text-align: center;">
                                <span class="EnlaceSimple label-campo" onclick="Margen_Nuevo(<?php echo $value["prospecto_id"]; ?>, <?php echo $value["producto_id"]; ?>, '2');">
                                    <strong> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </strong>
                                </span>
                            </td>

                            <td style="width: 10%; font-weight: normal; text-align: center;">
                                <?php echo $value["producto_nombre"]; ?>
                            </td>

                            <td style="width: 10%; font-weight: normal; text-align: center;">
                                <?php echo $value["producto_venta_medida"]; ?>
                            </td>

                            <td style="width: 9%; font-weight: normal; text-align: center; font-weight: bold;">
                                <?php echo number_format($value["producto_venta_costo"], 2, ',', '.'); ?>
                            </td>
                            
                            <td style="width: 1%; font-weight: normal; text-align: center; font-weight: bold; font-style: italic;">
                                <?php 
                                
                                if($this->mfunciones_generales->GetValorCatalogo($value["producto_opcion"], 'producto_opcion') == 'C')
                                {
                                    echo '
                                    <span class="EnlaceSimple label-campo" onclick="DetalleProducto(' . $value["producto_id"] . ');">
                                        <strong> <i class="fa fa-cubes" aria-hidden="true"></i> </strong>
                                    </span>';
                                }
                                else
                                {
                                    echo $this->mfunciones_generales->GetValorCatalogo($value["producto_opcion"], 'producto_opcion'); 
                                }
                                
                                ?>
                            </td>

                            <td style="width: 15%; font-weight: normal; text-align: center;">
                                <?php echo $value["producto_aclaracion"]; ?>
                            </td>

                            <td style="width: 10%; font-weight: normal; text-align: center;">
                                <?php echo number_format($value["producto_compra_cantidad"], 2, ',', '.'); ?>
                            </td>
                            <td style="width: 10%; font-weight: normal; text-align: center;">
                                <?php echo $value["producto_compra_medida"]; ?>
                            </td>
                            <td style="width: 10%; font-weight: normal; text-align: center;">
                                <?php echo number_format($value["producto_compra_precio"], 2, ',', '.'); ?>
                            </td>
                            <td style="width: 10%; font-weight: normal; text-align: center;">
                                <?php echo number_format($value["producto_unidad_venta_unidad_compra"], 2, ',', '.'); ?>
                            </td>
                            <td style="width: 10%; font-weight: normal; text-align: center;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($value["producto_categoria_mercaderia"], 'categoria_mercaderia'); ?>
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
            echo "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> Aún No se Registró Información <br /> Puede crear desde NUEVO REGISTRO </div>";
        }

        ?>

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