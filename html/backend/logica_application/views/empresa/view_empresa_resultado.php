    <?php

    if(isset($arrRespuesta[0]))
    {
        if($registrado_sistema == 0)
        {
            echo $this->lang->line('verifique_nit_ya_registrado');
        }
        else
        {
    ?>
            <table class="tblListas Centrado" style="width: 80% !important;" border="0">
                
                <?php $strClase = "FilaGris"; ?>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                <tr class="<?php echo $strClase; ?>">
                    <th style="width:30%; font-weight: bold;">
                        <strong><?php echo $this->lang->line('empresa_parametro'); ?></strong>
                    </th>

                    <th style="width:70%;">
                        <strong><?php echo $this->lang->line('empresa_valor'); ?></strong>
                    </th>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                <tr class="<?php echo $strClase; ?>">
                    <td style="width:30%; font-weight: bold;">
                        <strong><?php echo $this->lang->line('empresa_nit'); ?></strong>
                    </td>

                    <td style="width:70%;">
                        <?php echo $arrRespuesta[0]['parent_nit']; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                <tr class="<?php echo $strClase; ?>">
                    <td style="width:30%; font-weight: bold;">
                        <strong><?php echo $this->lang->line('empresa_tipo_sociedad_detalle'); ?></strong>
                    </td>

                    <td style="width:70%;">
                        <?php echo $arrRespuesta[0]['parent_tipo_sociedad_detalle']; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                <tr class="<?php echo $strClase; ?>">
                    <td style="width:30%; font-weight: bold;">
                        <strong><?php echo $this->lang->line('empresa_nombre_legal'); ?></strong>
                    </td>

                    <td style="width:70%;">
                        <?php echo $arrRespuesta[0]['parent_nombre_legal']; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                <tr class="<?php echo $strClase; ?>">
                    <td style="width:30%; font-weight: bold;">
                        <strong><?php echo $this->lang->line('empresa_nombre_fantasia'); ?></strong>
                    </td>

                    <td style="width:70%;">
                        <?php echo $arrRespuesta[0]['parent_nombre_fantasia']; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                <tr class="<?php echo $strClase; ?>">
                    <td style="width:30%; font-weight: bold;">
                        <strong><?php echo $this->lang->line('empresa_mcc_detalle'); ?></strong>
                    </td>

                    <td style="width:70%;">
                        <?php echo $arrRespuesta[0]['parent_mcc_detalle']; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                <tr class="<?php echo $strClase; ?>">
                    <td style="width:30%; font-weight: bold;">
                        <strong><?php echo $this->lang->line('empresa_rubro_detalle'); ?></strong>
                    </td>

                    <td style="width:70%;">
                        <?php echo $arrRespuesta[0]['parent_rubro_detalle']; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                <tr class="<?php echo $strClase; ?>">
                    <td style="width:30%; font-weight: bold;">
                        <strong><?php echo $this->lang->line('empresa_perfil_comercial_detalle'); ?></strong>
                    </td>

                    <td style="width:70%;">
                        <?php echo $arrRespuesta[0]['parent_perfil_comercial_detalle']; ?>
                    </td>
                </tr>
                
            </table>

    <?php
        }
    }
    else
    {            
        echo $this->lang->line('verifique_nit_mantenimiento');
    }

    ?>