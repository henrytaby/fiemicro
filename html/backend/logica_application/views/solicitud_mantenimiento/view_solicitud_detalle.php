
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
                        <?php echo $this->lang->line('solicitud_nombre_empresa'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrRespuesta[0]["solicitud_nombre_empresa"]; ?>
                    </td>
                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">
                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('empresa_nit'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrRespuesta[0]["solicitud_nit"]; ?>
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
                        <?php echo $this->lang->line('mantenimiento_tareas'); ?>
                    </td>

                    <td style="width: 70%;">

                        <?php                                            
                            if(isset($arrTareas[0]))
                            {
                                foreach ($arrTareas as $key => $value) 
                                {
                                    echo ' <i class="fa fa-wrench" aria-hidden="true"></i> ' . $value["tarea_detalle"];
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

                <?php

                if($arrRespuesta[0]["solicitud_otro"] == 1)
                {
                ?>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">
                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('mantenimiento_otro'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["solicitud_otro_detalle"]; ?>
                        </td>
                    </tr>

                <?php
                }
                ?>
                
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