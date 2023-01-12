    <?php

    if(isset($arrRespuesta[0]))
    {
        if($registrado_sistema == 1)
        {
            echo $this->lang->line('verifique_nit_solo_paystudio');
        }
        else
        {
    ?>
            <table class="tblListas Centrado" style="width: 100%;" border="0">
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="FilaCabecera">
                    <th style="width:15%;">
                        <strong><?php echo $this->lang->line('empresa_categoria_detalle'); ?></strong>
                    </td>

                    <th style="width:35%;">
                        <strong><?php echo $this->lang->line('solicitud_nombre_empresa'); ?></strong>
                    </td>

                    <th style="width:35%;">
                        <strong><?php echo $this->lang->line('ejecutivo_nombre'); ?></strong>
                    </td>

                    <th style="width:15%;">

                        <strong><?php echo $this->lang->line('TablaOpciones'); ?></strong>

                    </td>
                </tr>

                <?php

                $i = 0;

                foreach ($arrRespuesta as $key => $value)
                {

                ?>
                    <?php $strClase = "FilaBlanca"; ?>
                
                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">
                        <td style="width: 15%; text-align: center;">
                            <?php echo $value['empresa_categoria_detalle']; ?>
                        </td>

                        <td style="width: 35%; text-align: center;">
                            <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleEmpresa('<?php echo $value["empresa_id"]; ?>')">
                                <?php echo $value["empresa_nombre"]; ?>
                            </span>
                        </td>

                        <td style="width: 35%; text-align: center;">
                            <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleUsuario(0, '<?php echo $value["usuario_id"]; ?>')">
                                <?php echo $value["ejecutivo_nombre"]; ?>
                            </span>
                        </td>

                        <td style="width: 15%;">

                            <?php

                            $marcado = '';

                            if($i == 0)
                            {
                                $marcado = "checked='checked'";
                            }

                            ?>

                            <input id="codigo_empresa<?php echo $i; ?>" name="codigo_empresa" type="radio" class="" <?php echo $marcado; ?> value="<?php echo $value["empresa_id"] . '|' . $value["ejecutivo_id"]; ?>" />
                            <label for="codigo_empresa<?php echo $i; ?>" class=""><span></span>Seleccionar</label>

                        </td>
                    </tr>

                <?php

                    $i++;
                }
                ?>

            </table>

    <?php
        }
    }
    else
    {            
        echo $this->lang->line('verifique_nit_mantenimiento');
    }

    ?>