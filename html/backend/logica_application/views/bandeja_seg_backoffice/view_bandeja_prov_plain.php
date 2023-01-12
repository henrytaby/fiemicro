<?php

    $cantidad_columnas=11;

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

                <th style="border-bottom: 0.5px solid #464646; width:5%;">Estado</th>
                
                <th style="border-bottom: 0.5px solid #464646; width:5%;"> <?php echo $this->lang->line('regionaliza_nombre'); ?> </th>

                <th style="border-bottom: 0.5px solid #464646; width:5%;">
                   <?php echo $this->lang->line('prospecto_codigo'); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:15%;">
                   <?php echo $this->lang->line('prospecto_nombre_empresa'); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:10%;">
                   <?php echo $this->lang->line('solicitud_categoria_empresa'); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:15%;">
                   <?php echo $this->lang->line('ejecutivo_nombre'); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:10%;">
                   <?php echo $this->lang->line('prospecto_fecha_asignaccion'); ?> </th>
                <th style="border-bottom: 0.5px solid #464646; width:10%;">
                   <?php echo $this->lang->line('solicitud_servicios'); ?> </th>

                <th style="border-bottom: 0.5px solid #464646; width:5%;">
                   <?php echo $this->lang->line('prov_cod_mant'); ?> </th> </th>
                <th style="border-bottom: 0.5px solid #464646; width:15%;">
                   <?php echo $this->lang->line('prov_usuario_asignado'); ?> </th>

                <th style="border-bottom: 0.5px solid #464646; width:5%;">
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

                    $respuesta = $this->mfunciones_generales->DatosProsProvisioning($value["prospecto_provisioning_mant"], $value["prospecto_id"]);
                    
                    ?> 
                    <tr class="">

                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $this->mfunciones_generales->TiempoEtapaColor_plain($value["tiempo_etapa"]); ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                             <?php echo $this->mfunciones_generales->GetProspectoRegion($value["prospecto_id"]); ?>
                        </td>

                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo PREFIJO_PROSPECTO . $value["prospecto_id"]; ?>
                        </td>

                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["empresa_nombre"]; ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                                <?php
                                if($value["empresa_categoria_codigo"] == 1)
                                {
                                    echo $this->lang->line('categoria_empresa_comercio');
                                }
                                else
                                {
                                    echo $this->lang->line('categoria_empresa_sucrusal');
                                }                                                
                                ?>
                        </td>

                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["ejecutivo_nombre"]; ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $value["prospecto_fecha_asignacion"]; ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: left;">
                            <?php echo $respuesta->servicios_solicitados; ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $respuesta->codigo_mant; ?>
                        </td>
                        
                        <td align="center" style="border-bottom: 0.5px solid #464646; text-align: center;">
                            <?php echo $respuesta->nombre_provisioning; ?>
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