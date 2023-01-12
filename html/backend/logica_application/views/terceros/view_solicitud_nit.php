
    <div style="overflow-y: auto;">
    
        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('DetalleNITTitulo') . ' - ' . $nit; ?></div>
        
        <div style="clear: both"></div>

        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php

            if(isset($arrRespuesta[0]))
            {
                if($arrRespuesta[0]['parent_id'] == -1)
                {
                    echo "<div align='center'><div class='mensaje_advertencia'> " . $this->lang->line('nit_advertencia') . " </div></div> <br />";
                }
                
            ?>

                <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                    <?php $strClase = "FilaGris"; ?>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_nit'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["parent_nit"]; ?>
                        </td>
                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">
                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_adquiriente_detalle'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["parent_adquiriente_detalle"]; ?>
                        </td>
                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">
                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_tipo_sociedad_detalle'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["parent_tipo_sociedad_detalle"]; ?>
                        </td>
                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_nombre_legal'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["parent_nombre_legal"]; ?>
                        </td>

                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_nombre_fantasia'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["parent_nombre_fantasia"]; ?>
                        </td>

                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_rubro_detalle'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["parent_rubro_detalle"]; ?>
                        </td>

                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_perfil_comercial_detalle'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["parent_perfil_comercial_detalle"]; ?>
                        </td>

                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_mcc_detalle') . ' Código'; ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["parent_mcc_codigo"]; ?>
                        </td>

                    </tr>
                    
                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_mcc_detalle'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["parent_mcc_detalle"]; ?>
                        </td>

                    </tr>

                </table>

            <?php

            }

            else
            {            
                echo "<div align='center'><div class='mensaje_advertencia'> " . $this->lang->line('no_nit_advertencia') . " </div></div> <br />";
            }

            ?>

        </form>

        <br /><br />
    </div>