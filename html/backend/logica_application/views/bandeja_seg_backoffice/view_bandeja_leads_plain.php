<?php

    $cantidad_columnas=8;

?>

    <table align="center" id="divDetalle" class="" cellpadding="0" border="0" style="border: 1px solid #f5f5f5; width: 440px; font-size: 12px; font-family: Arial, sans-serif; text-align: center;">

        <tr class="" style="background-color: #f5f5f5;">

            <td align="center" style="width: 100%; font-weight: bold; text-align: center; padding: 5px 0px;">
                <strong>
                    <?php
                        if($etapa_categoria == 50)
                        {
                            echo 'Tiempo Total de Flujo de Afiliación: ' . $tiempo_etapa_asignado . ' hora(s)';
                        }
                        else
                        {
                            echo 'Tiempo asignado a la etapa ' . $etapa_nombre . ': ' . $tiempo_etapa_asignado . ' hora(s)';
                        }
                    ?>
                </strong>
            </td>

        </tr>

        <tr class="">

            <td align="center" style="width: 100%; font-weight: bold; text-align: center;">
                <strong><?php echo $arrResumen[0]['contador_0']; ?> Atrasado(s)</strong>
            </td>

        </tr>

    </table>

    <br />

    <table align="center" id="divDetalle" class="" cellpadding="3" border="0" style="width: 750px; font-size: 10px; font-family: Arial, sans-serif; text-align: center; border: 1px solid #f5f5f5;">
        <thead>

            <tr class="" style="background-color: #f5f5f5;">

                <th style="border-bottom: 0.5px solid #464646; width:10%;">Estado</th>
                
                <th style="border-bottom: 0.5px solid #464646; width:15%;"> <?php echo $this->lang->line('empresa_departamento_detalle'); ?> </th>

                <th style="border-bottom: 0.5px solid #464646; width:15%;">
                   <?php echo $this->lang->line('solicitud_nombre_empresa'); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:15%;">
                   <?php echo $this->lang->line('solicitud_nombre_persona'); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:10%;">
                   <?php echo $this->lang->line('solicitud_telefono'); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:15%;">
                   <?php echo $this->lang->line('solicitud_fuente_lead'); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:10%;">
                   <?php echo $this->lang->line('solicitud_fecha'); ?> </th>

                <th style="border-bottom: 0.5px solid #464646; width:10%;">
                   Total Horas </th>

            </tr>
        </thead>
        <tbody>
            <?php

            $mostrar = true;
            if (count($arrRespuesta[0]) == 0) 
            {
                $mostrar = false;
            }

            if ($mostrar) 
            {                
                $i=0;
                $strClase = "FilaBlanca";
                foreach ($arrRespuesta as $key => $value) 
                {                    
                    $i++;

                    ?> 
                    <tr class="">

                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $this->mfunciones_generales->TiempoEtapaColor_plain($value["tiempo_etapa"]); ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                             <?php echo $value["solicitud_ciudad_detalle"]; ?>
                        </td>

                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["solicitud_nombre_empresa"]; ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["solicitud_nombre_persona"]; ?>
                        </td>

                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["solicitud_telefono"]; ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["fuente_nombre"]; ?>
                        </td>

                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["solicitud_fecha"]; ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["suma_horas"]; ?>
                        </td>
                        
                    </tr>
                <?php
                }
                ?>
                </tbody>
                <?php
                $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca";
                //endfor;
            }
            else 
            {
            ?>
            <tr>
                <td align="center" style="border-bottom: 0.5px solid #464646; width: 55%; color: #25728c;" colspan="<?php echo $cantidad_columnas; ?>">
                    <br><br>
                    <?php echo $this->lang->line('TablaNoPendientes'); ?>
                    <br><br>
                </td>
            </tr>
        <?php } ?>
    </table>