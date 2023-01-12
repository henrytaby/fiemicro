
    <div style="overflow-y: auto; height: 400px;">
    
        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('DetalleRegistroTitulo'); ?></div>
        
        <div style="clear: both"></div>

        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php

            if(count($arrRespuesta[0]) > 0)
            {

            ?>

                <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                    <?php $strClase = "FilaGris"; ?>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_nombre_persona'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_nombre_persona"]; ?>
                        </td>
                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">
                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_nombre_empresa'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_nombre_empresa"]; ?>
                        </td>
                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">
                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_ciudad'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_ciudad_detalle"]; ?>
                        </td>
                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_telefono'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_telefono"]; ?>
                        </td>

                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_email'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_email"]; ?>
                        </td>

                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_direccion_literal'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_direccion_literal"]; ?>
                        </td>

                    </tr>
                    
                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_direccion_geo'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php
                            
                            if($arrRespuesta[0]["solicitud_direccion_geo"] == '')
                            {
                                echo 'No registrado';
                            }
                            else
                            {
                                echo 'Registrado';
                            }
                            
                            ?>
                        </td>

                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_rubro'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_rubro_detalle"]; ?>
                        </td>

                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_fecha'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_fecha"]; ?>
                        </td>

                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_ip'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_ip"]; ?>
                        </td>

                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_estado'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_estado"]; ?>
                        </td>

                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_servicios'); ?>
                        </td>

                        <td style="width: 70%;">

                            <?php                                            
                                if(isset($arrServicios[0]))
                                {
                                    foreach ($arrServicios as $key => $value) 
                                    {
                                        echo ' <i class="fa fa-dot-circle-o" aria-hidden="true"></i> ' . $value["servicio_detalle"];
                                        echo "<br />";
                                    }                                
                                }
                                else
                                {
                                    echo $this->lang->line('TablaNoRegistros');
                                }
                            ?>


                        </td>

                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_observacion'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_observacion"]; ?>
                        </td>

                    </tr>

                </table>

            <?php

            }

            else
            {            
                echo $this->lang->line('TablaNoResultados');
            }

            ?>

        </form>

        <br /><br />
    </div>