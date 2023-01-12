<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<div style="font-size: 14px; font-family: 'Open Sans', Arial, sans-serif; text-align: center; font-weight: bold;">REPORTE - <?php echo mb_strtoupper(sprintf($this->lang->line('NormSupervisionTitulo'), $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app'))); echo '<br /> <span style="font-size: 11px; font-style: italic;"> Listando ' . number_format($resultado->cuenta, 0, '.', ',') . ' registro(s) </span>'; ?> </div>

<br /> 

<?php

    if($resultado->valoresFiltro != '')
    {
        echo '  <table align="center" id="divDetalle" class="tblListas Centrado " cellspacing="0" cellpadding="5" border="1" style="width: 100%; font-size: 9px; font-family: \'Open Sans\', Arial, sans-serif; text-align: justify;">
                    <tr>
                        <td colspan="11">
                            ' . $resultado->valoresFiltro . '
                        </td>
                    </tr>
                </table>
            ';

        
        echo '<br />';
    }
    
?>

    <table align="center" id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 100%; font-size: 10px; font-family: 'Open Sans', Arial, sans-serif; text-align: center;">
        <thead>

            <tr class="FilaCabecera">

                <!-- Similar al EXCEL -->

                <th style="width:5%;">
                   <?php echo $this->lang->line('norm_col_codigo'); ?> </th>
                <th style="width:9%;">
                   <?php echo $this->lang->line('norm_col_agencia'); ?> </th>
                <th style="width:10%;">
                   <?php echo $this->lang->line('norm_col_agente'); ?> </th>
                <th style="width:10%;">
                   <?php echo $this->lang->line('norm_col_cliente'); ?> </th>
                <th style="width:10%;">
                   <?php echo $this->lang->line('norm_col_num_proceso'); ?> </th>
                <th style="width:9%;">
                   <?php echo $this->lang->line('norm_col_estado'); ?> </th>
                <th style="width:10%;">
                   <?php echo $this->lang->line('norm_col_rel_cred'); ?> </th>
                <th style="width:10%;">
                   <?php echo $this->lang->line('norm_col_finalizacion'); ?> </th>
                <th style="width:9%;">
                   <?php echo $this->lang->line('norm_col_fec_registro'); ?> </th>
                <th style="width:9%;">
                   <?php echo $this->lang->line('norm_col_fec_visita'); ?> </th>
                <th style="width:9%;">
                   <?php echo $this->lang->line('norm_col_fec_comp_pago'); ?> </th>

                <!-- Similar al EXCEL -->

            </tr>
        </thead>
        <tbody>
            <?php
            $strClase = "FilaBlanca";
            foreach ($resultado->filas as $key => $value) 
            {
                ?> 
                <tr class="<?php echo $strClase; ?>">

                    <!-- Similar al EXCEL -->

                    <td style="text-align: center;">
                        <?php
                        echo $this->lang->line('norm_prefijo') . $value["norm_id"];
                        ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo $value["agencia_nombre"]; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo $value["ejecutivo_nombre"]; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo $value["cliente_nombre"]; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo $value["registro_num_proceso"]; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo $value["registro_consolidado"] . ($value["norm_ultimo_paso_check"] ? '<br /><i>¡Registro Pendiente!</i>' : ''); ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo $value["norm_rel_cred"]; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo $value["norm_finalizacion"]; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo $value["fecha_registro"]; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo $value["cv_fecha"]; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo $value["cv_fecha_compromiso"] . ($value['cv_fecha_compromiso_check'] ? '<br />' . $this->lang->line('norm_reporte_vencido_error') : ''); ?>
                    </td>

                    <!-- Similar al EXCEL -->

                </tr>
            <?php
            }
            ?>
            </tbody>
    </table>
