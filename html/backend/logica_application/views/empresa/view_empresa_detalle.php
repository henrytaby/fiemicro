    
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
                            <?php echo $this->lang->line('empresa_consolidada_detalle'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["empresa_consolidada_detalle"]; ?>
                        </td>
                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_categoria_detalle'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["empresa_categoria_detalle"]; ?>
                        </td>
                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_adquiriente_detalle'); ?>
                        </td>

                        <td style="width: 70%;">
                            Fundempresa
                        </td>
                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_nombre_legal'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["empresa_nombre_legal"]; ?>
                        </td>
                    </tr>

                    
                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_nombre_referencia'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["empresa_nombre_referencia"]; ?>
                        </td>
                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_email'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["empresa_email"]; ?>
                        </td>
                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_departamento_detalle'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["empresa_departamento_detalle"]; ?>
                        </td>
                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_municipio_detalle'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["empresa_municipio_detalle"]; ?>
                        </td>
                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_zona_detalle'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["empresa_zona_detalle"]; ?>
                        </td>
                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('empresa_direccion_literal'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["empresa_direccion_literal"]; ?>
                        </td>
                    </tr>
                    
                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('ejecutivo_asignado_nombre'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["ejecutivo_asignado_nombre"]; ?>
                        </td>
                    </tr>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('ejecutivo_asignado_contacto'); ?>
                        </td>

                        <td style="width: 70%;">
                            <?php echo $arrRespuesta[0]["ejecutivo_asignado_contacto"]; ?>
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