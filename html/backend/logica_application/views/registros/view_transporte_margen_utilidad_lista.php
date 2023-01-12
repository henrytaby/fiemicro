<div class="tab-content">

    <div id="tab_margen_egresos" class="tab-pane fade in active">
        
        <div style="text-align: right;">

            <span class="EnlaceSimple label-campo" onclick="MargenTransporte_Nuevo('<?php echo $estructura_id; ?>', '-1', '0');">
                <strong> <i class="fa fa-plus-square" aria-hidden="true"></i> NUEVO REGISTRO </strong>
            </span>

            <br /><br />

        </div>
        
        <?php

        $suma_costo = 0;
        $suma_ingreso = 0;
        
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

                    <td style="width: 20%; font-weight: bold; text-align: center;">
                        <?php echo $this->lang->line('margen_nombre'); ?>
                    </td>

                    <td style="width: 20%; font-weight: bold; text-align: center;">
                        <?php echo $this->lang->line('margen_cantidad'); ?>
                    </td>

                    <td style="width: 20%; font-weight: bold; text-align: center;">
                        <?php echo $this->lang->line('margen_unidad_medida'); ?>
                    </td>

                    <td style="width: 15%; font-weight: bold; text-align: center;">
                        Costo Unitario
                    </td>

                    <td style="width: 15%; font-weight: bold; text-align: center;">
                        Costo Total
                    </td>

                </tr>

                <?php

                if ($mostrar) 
                {
                    $strClase = "FilaBlanca";
                    foreach ($arrRespuesta as $key => $value) 
                    {
                        $suma_costo = $suma_costo + $value["margen_monto_total"];
                ?>
                        <tr class="FilaBlanca">

                            <td style="width: 10%; font-weight: bold; text-align: center;" onclick="MargenTransporte_Nuevo(<?php echo $value["prospecto_id"]; ?>, <?php echo $value["margen_id"]; ?>, '0');">
                                <span class="EnlaceSimple label-campo">
                                    <strong> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </strong>
                                </span>
                            </td>

                            <td style="width: 20%; font-weight: normal; text-align: center;">
                                <?php echo $value["margen_nombre"]; ?>
                            </td>

                            <td style="width: 20%; font-weight: normal; text-align: center;">
                                <?php echo number_format($value["margen_cantidad"], 2, ',', '.'); ?>
                            </td>

                            <td style="width: 20%; font-weight: normal; text-align: center; font-weight: bold;">
                                <?php echo $value["margen_unidad_medida"]; ?>
                            </td>
                            
                            <td style="width: 15%; font-weight: normal; text-align: center; font-weight: bold; ">
                                <?php echo number_format($value["margen_monto_unitario"], 2, ',', '.'); ?>
                            </td>

                            <td style="width: 15%; font-weight: normal; text-align: center; font-weight: bold; ">
                                <?php echo number_format($value["margen_monto_total"], 2, ',', '.'); ?>
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
                            <?php echo number_format($suma_costo, 2, ',', '.'); ?>
                        </td>

                    </tr>

            </table>

            <br />

        <?php

        }

        else
        {            
            echo "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> Aún No se Registró Información <br /> Puede crear desde NUEVO REGISTRO </div>";

            echo '<input type="hidden" id="total_suma_frecuencia" value="0" />';
        }

        ?>

    </div>
    
    <div id="tab_margen_ingresos" class="tab-pane fade">
        
        <div style="text-align: right;">

            <span class="EnlaceSimple label-campo" onclick="MargenTransporte_Nuevo('<?php echo $estructura_id; ?>', '-1', '1');">
                <strong> <i class="fa fa-plus-square" aria-hidden="true"></i> NUEVO REGISTRO </strong>
            </span>

            <br /><br />

        </div>
        
        <?php

        $mostrar = false;

        if(count($arrRespuesta2[0]) > 0)
        {
            $mostrar = true;
        ?>

            <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

                <tr class="FilaGris">

                    <td style="width: 10%; font-weight: bold; text-align: center;">
                        Opc.
                    </td>

                    <td style="width: 15%; font-weight: bold; text-align: center;">
                        <?php echo $this->lang->line('margen_nombre'); ?>
                    </td>

                    <td style="width: 15%; font-weight: bold; text-align: center;">
                        <?php echo $this->lang->line('margen_cantidad'); ?>
                    </td>

                    <td style="width: 15%; font-weight: bold; text-align: center;">
                        <?php echo $this->lang->line('margen_unidad_medida'); ?>
                    </td>

                    <td style="width: 15%; font-weight: bold; text-align: center;">
                        <?php echo $this->lang->line('margen_pasajeros'); ?>
                    </td>
                    
                    <td style="width: 15%; font-weight: bold; text-align: center;">
                        Precio Unitario
                    </td>

                    <td style="width: 15%; font-weight: bold; text-align: center;">
                        Ingreso Total
                    </td>

                </tr>

                <?php

                if ($mostrar) 
                {
                    $strClase = "FilaBlanca";
                    foreach ($arrRespuesta2 as $key => $value) 
                    {
                        $suma_ingreso = $suma_ingreso + $value["margen_monto_total"];
                ?>
                        <tr class="FilaBlanca">

                            <td style="width: 10%; font-weight: bold; text-align: center;" onclick="MargenTransporte_Nuevo(<?php echo $value["prospecto_id"]; ?>, <?php echo $value["margen_id"]; ?>, '1');">
                                <span class="EnlaceSimple label-campo">
                                    <strong> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </strong>
                                </span>
                            </td>

                            <td style="width: 15%; font-weight: normal; text-align: center;">
                                <?php echo $value["margen_nombre"]; ?>
                            </td>

                            <td style="width: 15%; font-weight: normal; text-align: center;">
                                <?php echo number_format($value["margen_cantidad"], 2, ',', '.'); ?>
                            </td>

                            <td style="width: 15%; font-weight: normal; text-align: center; font-weight: bold;">
                                <?php echo $value["margen_unidad_medida"]; ?>
                            </td>
                            
                            <td style="width: 15%; font-weight: normal; text-align: center; font-weight: bold;">
                                <?php echo number_format($value["margen_pasajeros"], 2, ',', '.'); ?>
                            </td>
                            
                            <td style="width: 15%; font-weight: normal; text-align: center; font-weight: bold; ">
                                <?php echo number_format($value["margen_monto_unitario"], 2, ',', '.'); ?>
                            </td>

                            <td style="width: 15%; font-weight: normal; text-align: center; font-weight: bold; ">
                                <?php echo number_format($value["margen_monto_total"], 2, ',', '.'); ?>
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
                        <?php echo number_format($suma_ingreso, 2, ',', '.'); ?>
                    </td>

                </tr>
                        
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

<input type="hidden" id="margen_utilidad_bruta" value="<?php echo ($suma_ingreso!=0 ? (($suma_ingreso-$suma_costo)/$suma_ingreso)*100 : 0); ?>" />

<script type="text/javascript">
    
    $(function(){
        
        <?php
        
            if($tab == "1")
            {
                echo '$("#tab_margen_ingresos").addClass(" in active"); $("#tab_margen_egresos").removeClass(" in active");';
            }
        ?>
                
        $("#titulo_margen_utilidad_bruta").html((parseFloat($("#margen_utilidad_bruta").val() || 0)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ',') + '%');
        
    });
    
</script>