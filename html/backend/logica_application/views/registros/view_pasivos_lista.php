<?php

$mostrar = false;

if(count($arrRespuesta[0]) > 0)
{
    $mostrar = true;
?>

    <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

        <tr class="FilaGris">

            <td style="width: 5%; font-weight: bold; text-align: center;">
                Opc.
            </td>

            <td style="width: 15%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('pasivo_acreedor'); ?>
            </td>

            <td style="width: 10%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('pasivo_tipo'); ?>
            </td>

            <td style="width: 10%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('pasivo_saldo'); ?>
            </td>

            <td style="width: 15%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('pasivo_periodo'); ?>
            </td>

            <td style="width: 10%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('pasivo_cuota_periodica'); ?>
            </td>

            <td style="width: 10%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('pasivo_cuota_mensual'); ?>
            </td>

            <td style="width: 10%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('pasivo_rfto'); ?>
            </td>

            <td style="width: 15%; font-weight: bold; text-align: center;">
                <?php echo $this->lang->line('pasivo_destino'); ?>
            </td>
            
        </tr>

        <?php

        if ($mostrar) 
        {
            $suma_saldo = 0;
            $suma_cuota = 0;
            
            $strClase = "FilaBlanca";
            foreach ($arrRespuesta as $key => $value) 
            {
                $suma_saldo += $value["pasivo_saldo"];
                $suma_cuota += ($value['pasivo_rfto']==0 ? $value["pasivo_cuota_mensual"] : 0);
        ?>
                <tr class="FilaBlanca">

                    <td style="width: 5%; font-weight: bold; text-align: center;" onclick="Pasivos_Nuevo(<?php echo $value["prospecto_id"]; ?>, <?php echo $value["pasivo_id"]; ?>, '1');">
                        <span class="EnlaceSimple label-campo">
                            <strong> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </strong>
                        </span>
                    </td>

                    <td style="width: 15%; font-weight: normal; text-align: center;">
                        <?php echo $value["pasivo_acreedor"]; ?>
                    </td>

                    <td style="width: 10%; font-weight: normal; text-align: center;">
                        <?php echo $value["pasivo_tipo"]; ?>
                    </td>

                    <td style="width: 10%; font-weight: normal; text-align: center;">
                        <?php echo number_format($value["pasivo_saldo"], 2, ',', '.'); ?>
                    </td>

                    <td style="width: 15%; font-weight: normal; text-align: center;">
                        <?php echo $this->mfunciones_generales->GetValorCatalogo($value['pasivo_periodo'], 'frecuencia_dias'); ?>
                    </td>

                    <td style="width: 10%; font-weight: normal; text-align: center;">
                        <?php echo number_format($value["pasivo_cuota_periodica"], 2, ',', '.'); ?>
                    </td>

                    <td <?php echo ($value['pasivo_rfto']==0 ? 'class="FilaSeleccion"' : ''); ?> style="width: 10%; font-weight: normal; text-align: center; ">
                        <?php echo number_format($value["pasivo_cuota_mensual"], 2, ',', '.'); ?>
                    </td>

                    <td style="width: 10%; font-weight: normal; text-align: center;">
                        <?php echo $this->mfunciones_generales->GetValorCatalogo($value['pasivo_rfto'], 'si_no'); ?>
                    </td>

                    <td style="width: 15%; font-weight: normal; text-align: center;">
                        <?php echo $value["pasivo_destino"]; ?>
                    </td>

                </tr>

        <?php
            }
        }
        ?>

        <tr class="FilaGris">

            <td colspan="3" style="font-weight: bold; text-align: center;">
                Total Deuda Directa
            </td>
            <td style="font-weight: bold; text-align: center;">
                <?php echo number_format($suma_saldo, 2, ',', '.'); ?>
            </td>
            <td colspan="2" style="font-weight: bold; text-align: center;">
                Total Cuotas al mes
            </td>
            <td style="font-weight: bold; text-align: center;">
                <?php echo number_format($suma_cuota, 2, ',', '.'); ?>
            </td>
            <td colspan="2" style="font-weight: bold; text-align: center;">
                
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