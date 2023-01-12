<?php

$mostrar = false;

if(count($arrRespuesta[0]) > 0)
{
    $mostrar = true;
?>

    <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

        <tr class="FilaGris">

            <td style="width: 6%; font-weight: bold; text-align: center;">
                Opc.
            </td>

            <td style="width: 9%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('materia_nombre'); ?>
            </td>

            <td style="width: 8%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('materia_frecuencia'); ?>
            </td>

            <td style="width: 9%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('materia_unidad_compra'); ?>
            </td>

            <td style="width: 8%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('materia_unidad_compra_cantidad'); ?>
            </td>

            <td style="width: 9%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('materia_unidad_uso'); ?>
            </td>
            
            <td style="width: 9%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('materia_unidad_uso_cantidad'); ?>
            </td>
            
            <td style="width: 9%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('materia_unidad_proceso'); ?>
            </td>
            
            <td style="width: 9%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('materia_producto_medida'); ?>
            </td>
            
            <td style="width: 8%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('materia_producto_medida_cantidad'); ?>
            </td>
            
            <td style="width: 8%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('materia_precio_unitario'); ?>
            </td>
            
            <td style="width: 8%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('materia_ingreso_estimado'); ?>
            </td>
        </tr>

        <?php
        
        if ($mostrar) 
        {
            $suma_precio = 0;
            $strClase = "FilaBlanca";
            foreach ($arrRespuesta as $key => $value) 
            {
                $suma_precio += $value["materia_ingreso_estimado"];
        ?>
                <tr class="FilaBlanca">

                    <td style="width: 6%; font-weight: bold; text-align: center;" onclick="Materia_Nuevo(<?php echo $value["prospecto_id"]; ?>, <?php echo $value["materia_id"]; ?>);">
                        <span class="EnlaceSimple label-campo">
                            <strong> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </strong>
                        </span>
                    </td>

                    <td style="width: 9%; font-weight: normal; text-align: center;">
                        <?php echo $value["materia_nombre"]; ?>
                    </td>

                    <td style="width: 8%; font-weight: normal; text-align: center;">
                        <?php echo $value["materia_frecuencia"]; ?>
                    </td>
                    
                    <td style="width: 9%; font-weight: normal; text-align: center;">
                        <?php echo $value["materia_unidad_compra"]; ?>
                    </td>
                    
                    <td style="width: 8%; font-weight: normal; text-align: center;">
                        <?php echo number_format($value["materia_unidad_compra_cantidad"], 2, ',', '.'); ?>
                    </td>
                    
                    <td style="width: 9%; font-weight: normal; text-align: center;">
                        <?php echo $value["materia_unidad_uso"]; ?>
                    </td>
                    
                    <td style="width: 9%; font-weight: normal; text-align: center;">
                        <?php echo number_format($value["materia_unidad_uso_cantidad"], 2, ',', '.'); ?>
                    </td>
                    
                    <td style="width: 9%; font-weight: normal; text-align: center;">
                        <?php echo $value["materia_unidad_proceso"]; ?>
                    </td>
                    
                    <td style="width: 9%; font-weight: normal; text-align: center;">
                        <?php echo $value["materia_producto_medida"]; ?>
                    </td>
                    
                    <td style="width: 8%; font-weight: normal; text-align: center;">
                        <?php echo number_format($value["materia_producto_medida_cantidad"], 2, ',', '.'); ?>
                    </td>
                    
                    <td style="width: 8%; font-weight: normal; text-align: center;">
                        <?php echo number_format($value["materia_precio_unitario"], 2, ',', '.'); ?>
                    </td>
                    
                    <td style="width: 8%; font-weight: normal; text-align: center;">
                        <?php echo number_format($value["materia_ingreso_estimado"], 2, ',', '.'); ?>
                    </td>
                </tr>

        <?php
            }
        }
        ?>

            <tr class="FilaGris">

                <td colspan="11" style="font-weight: bold; text-align: center;">
                    SUMATORIA BS.
                </td>
                <td style="font-weight: bold; text-align: center;">
                    <?php echo number_format($suma_precio, 2, ',', '.'); ?>
                </td>
                
            </tr>
                
    </table>

    <br />

<?php

}

else
{            
    echo $this->lang->line('TablaNoRegistrosDetalle');
}

?>