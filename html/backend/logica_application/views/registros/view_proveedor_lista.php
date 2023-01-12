<?php

$mostrar = false;

if(count($arrRespuesta[0]) > 0)
{
    $mostrar = true;
?>

    <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

        <tr class="FilaGris">

            <td style="width: 16%; font-weight: bold; text-align: center;">
                Opc.
            </td>

            <td style="width: 20%; font-weight: bold; text-align: center;">
                Datos del proveedor
            </td>

            <td style="width: 16%; font-weight: bold; text-align: center;">
                Lugar de compra
            </td>

            <td style="width: 16%; font-weight: bold; text-align: center;">
                Frecuencia en dias
            </td>

            <td style="width: 16%; font-weight: bold; text-align: center;">
                Fecha Ultima Compra
            </td>

            <td style="width: 16%; font-weight: bold; text-align: center;">
                Importe
            </td>
            
        </tr>

        <?php
        
        if ($mostrar) 
        {
            $promedio_mensual_total = 0;
            
            $suma_importe = 0;
            $strClase = "FilaBlanca";
            foreach ($arrRespuesta as $key => $value) 
            {
                $promedio_mensual_total += ($value['proveedor_frecuencia_dias']!=0 ? (30/$value['proveedor_frecuencia_dias'])*$value['proveedor_importe'] : 0);
                
                $suma_importe = $suma_importe + $value["proveedor_importe"];
        ?>
                <tr class="FilaBlanca">

                    <td style="width: 16%; font-weight: bold; text-align: center;" onclick="Proveedor_Nuevo(<?php echo $value["prospecto_id"]; ?>, <?php echo $value["proveedor_id"]; ?>);">
                        <span class="EnlaceSimple label-campo">
                            <strong> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </strong>
                        </span>
                    </td>

                    <td style="width: 20%; font-weight: normal; text-align: center;">
                        <?php echo $value["proveedor_nombre"]; ?>
                    </td>

                    <td style="width: 16%; font-weight: normal; text-align: center;">
                        <?php echo $value["proveedor_lugar_compra"]; ?>
                    </td>

                    <td style="width: 16%; font-weight: normal; text-align: center;">
                        <?php echo $this->mfunciones_generales->GetValorCatalogo($value["proveedor_frecuencia_dias"], 'frecuencia_dias'); ?>
                    </td>

                    <td style="width: 16%; font-weight: normal; text-align: center;">
                        <?php echo $value["proveedor_fecha_ultima"]; ?>
                    </td>

                    <td style="width: 16%; font-weight: normal; text-align: center;">
                        <?php echo number_format($value["proveedor_importe"], 2, ',', '.'); ?>
                    </td>
                    
                </tr>

        <?php
            }
        }
        ?>

            <tr class="FilaGris">

                <td colspan="5" style="font-weight: bold; text-align: center;">
                    SUMATORIA BS.
                </td>
                <td style="font-weight: bold; text-align: center;">
                    <?php echo number_format($suma_importe, 2, ',', '.'); ?>
                </td>
                
            </tr>
                
    </table>

    <br />
    
    <input type="hidden" id="promedio_mensual_total" value="<?php echo $promedio_mensual_total; ?>" />

<?php

}

else
{            
    echo $this->lang->line('TablaNoRegistrosDetalle');
}

?>

<script type="text/javascript">
    
    $(function(){
        
        obtener_valores();
        
    });
    
</script>