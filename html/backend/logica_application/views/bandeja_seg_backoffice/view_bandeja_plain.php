<?php

    $cantidad_columnas=11;

?>

    <table align="center" id="divDetalle" class="" cellpadding="0" border="0" style="border: 1px solid #f5f5f5; width: 440px; font-size: 12px; font-family: Arial, sans-serif; text-align: center;">

        <tr class="" style="background-color: #f5f5f5;">

            <td align="center" style="width: 100%; font-weight: bold; text-align: center; padding: 5px 0px;">
                <strong>
                    <?php
                        if($etapa_categoria == 2)
                        {
                            if($etapa_verificar == 16)
                            {
                                echo $etapa_nombre;
                            }
                            else
                            {
                                echo 'Tiempo Total de Flujo Onboarding: ' . $tiempo_etapa_asignado . ' hora(s)';
                            }
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

    <table align="center" id="divDetalle" class="" cellpadding="3" border="0" style="width: 100%; font-size: 10px; font-family: Arial, sans-serif; text-align: center; border: 1px solid #f5f5f5;">
        <thead>

            <tr class="" style="background-color: #f5f5f5;">

                <th style="border-bottom: 0.5px solid #464646; width:10%;"> <?php echo $this->lang->line('regionaliza_nombre'); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:5%;"><?php echo $this->lang->line('prospecto_codigo'); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:10%;"> Tipo Registro </th>
                <th style="border-bottom: 0.5px solid #464646; width:10%;"><?php echo $this->lang->line('tercero_asistencia'); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:5%;"><?php echo $this->lang->line('tipo_cuenta'); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:10%;">Ciudad</th>
                <th style="border-bottom: 0.5px solid #464646; width:15%;"><?php echo $this->lang->line('terceros_columna3'); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:10%;"><?php echo $this->lang->line('terceros_columna4'); ?> </th>
                
                <th style="border-bottom: 0.5px solid #464646; width:10%;">
                   <?php echo ($etapa_verificar==16 ? 'Fecha ' . $this->lang->line('notificar_cierre_texto') : $this->lang->line('prospecto_fecha_asignaccion')); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:10%;">
                   <?php echo $this->lang->line('prospecto_etapa_actual'); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:5%;">
                   <?php echo ($etapa_verificar==16 ? 'Total Días' : 'Total Horas') ?> </th>

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
                $sw_prov = 0;
                
                $i=0;
                $strClase = "FilaBlanca";
                foreach ($arrRespuesta as $key => $value) 
                {                    
                    $i++;

                    ?> 
                    <tr class="">
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                             <?php echo $value["codigo_agencia_fie"]; ?>
                        </td>

                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo PREFIJO_TERCEROS . $value["terceros_id"]; ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["tipo_persona_id_detalle"]; ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["tercero_asistencia"]; ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["tipo_cuenta"]; ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["dir_localidad_ciudad"]; ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["nombre_completo"]; ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["carnet_identidad"]; ?>
                        </td>
                        
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["prospecto_fecha_asignacion"]; ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["etapa_nombre"]; ?>
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