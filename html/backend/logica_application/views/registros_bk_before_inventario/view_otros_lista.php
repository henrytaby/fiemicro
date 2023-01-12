<?php

$mostrar = false;

if(count($arrRespuesta[0]) > 0)
{
    $mostrar = true;
?>

    <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

        <tr class="FilaGris">

            <td style="width: 15%; font-weight: bold; text-align: center;">
                Opc.
            </td>

            <td style="width: 30%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('otros_descripcion_fuente'); ?>
            </td>

            <td style="width: 30%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('otros_descripcion_respaldo'); ?>
            </td>
            
            <td style="width: 25%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('otros_monto'); ?>
            </td>
        </tr>

        <?php
        
        if ($mostrar) 
        {
            $suma_importe = 0;
            $strClase = "FilaBlanca";
            foreach ($arrRespuesta as $key => $value) 
            {
                $suma_importe = $suma_importe + $value["otros_monto"];
        ?>
                <tr class="FilaBlanca">

                    <td style="width: 15%; font-weight: bold; text-align: center;" onclick="Otros_Ingresos_Nuevo(<?php echo $value["prospecto_id"]; ?>, <?php echo $value["otros_id"]; ?>);">
                        <span class="EnlaceSimple label-campo">
                            <strong> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </strong>
                        </span>
                    </td>

                    <td style="width: 30%; font-weight: normal; text-align: center;">
                        <?php echo $value["otros_descripcion_fuente"]; ?>
                    </td>

                    <td style="width: 30%; font-weight: normal; text-align: center;">
                        <?php echo $value["otros_descripcion_respaldo"]; ?>
                    </td>

                    <td style="width: 25%; font-weight: normal; text-align: center;">
                         <?php echo number_format($value["otros_monto"], 2, ',', '.'); ?>
                    </td>

                </tr>

        <?php
            }
        }
        ?>

            <tr class="FilaGris">

                <td colspan="3" style="font-weight: bold; text-align: center;">
                    TOTAL OTROS INGRESOS
                </td>
                <td style="font-weight: bold; text-align: center;">
                    <?php echo number_format($suma_importe, 2, ',', '.'); ?>
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