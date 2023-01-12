<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<div style="font-size: 14px; font-family: 'Open Sans', Arial, sans-serif; text-align: center; font-weight: bold;">ESTADO DE EVOLUCIÓN - <?php echo mb_strtoupper($etapa_nombre); echo '<br /> <span style="font-size: 11px; font-style: italic;"> Listando ' . number_format(count($arrRespuesta), 0, ',', '.') . ' registro(s) </span>'; ?> </div>

<br /> 

<?php

    if($ValoresFiltro != '')
    {
        echo '  <table align="center" id="divDetalle" class="tblListas Centrado " cellspacing="0" cellpadding="5" border="1" style="width: 100%; font-size: 9px; font-family: \'Open Sans\', Arial, sans-serif; text-align: justify;">
                    <tr>
                        <td colspan="12">
                            ' . $ValoresFiltro . '
                        </td>
                    </tr>
                </table>
            ';

        
        echo '<br />';
    }
    
?>

    <table align="center" id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 100%; font-size: 9px; font-family: 'Open Sans', Arial, sans-serif; text-align: center;">
        <thead>
        <tr>
            <th style="width: 5%;"> Código Cliente </th>
            <th style="width: 5%;"><?php echo $this->lang->line('terceros_columna1'); ?></th>
            <th style="width: 8%;"><?php echo $this->lang->line('general_solicitante_corto'); ?></th>
            <th style="width: 7%;"><?php echo $this->lang->line('general_ci'); ?></th>
            <th style="width: 8%;"><?php echo $this->lang->line('import_campana'); ?></th>
            <th style="width: 8%;"><?php echo $this->lang->line('import_agente'); ?></th>
            <th style="width: 8%;"><?php echo $this->lang->line('prospecto_etapa_actual'); ?></th>
            <th style="width: 8%;"><?php echo $this->lang->line('general_actividad'); ?></th>
            <th style="width: 8%;"><?php echo $this->lang->line('general_destino'); ?></th>
            <th style="width: 7%;"><?php echo $this->lang->line('prospecto_actividades'); ?></th>
            <th style="width: 7%;"><?php echo $this->lang->line('prospecto_jda_eval'); ?></th>
            <th style="width: 7%;"><?php echo $this->lang->line('sol_monto_bs'); ?></th>
            <th style="width: 7%;"><?php echo $this->lang->line('terceros_columna8'); ?></th>
            <th style="width: 7%;">Días Transcurridos</th>
        </tr>
        </thead>
        <?php
        if (isset($arrRespuesta[0])) 
        {
        ?>
            <tbody>
            <?php foreach ($arrRespuesta as $key => $value):?>
                <tr class="FilaBlanca">
                    <td align="center"><?php echo ($value["tipo"]==1 ? PREFIJO_PROSPECTO : 'SOL_') . $value["prospecto_id"]?></td>
                    <td align="center"><?php echo $value["agencia_nombre"]; ?></td>
                    <td align="center"><?php echo $value["general_solicitante"]; ?></td>
                    <td align="center"><?php echo $value["general_ci"] . ' ' . $value["general_ci_extension"]; ?></td>
                    <td align="center"><?php echo $value["camp_nombre"]?></td>
                    <td align="center"><?php echo $value["ejecutivo_nombre"]?></td>
                    <td align="center"><?php echo $value["etapa_nombre"]?></td>
                    <td align="center"><?php echo $value["general_actividad"]?></td>
                    <td align="center"><?php echo $value["general_destino"]?></td>
                    <td align="justify"><?php echo ($value["tipo"]==1 ? $this->mfunciones_generales->GetValorCatalogo($value["prospecto_id"], 'lead_actividades_plain') : $this->lang->line('TablaNoRegistrosMinimo')); ?></td>
                    <td align="center"><?php echo $this->mfunciones_generales->GetValorCatalogo($value["prospecto_jda_eval"], 'prospecto_evaluacion'); ?></td>
                    <td align="center"><?php echo $value["sol_monto_bs"]?></td>
                    <td align="center"><?php echo $value["fecha_registro"]?></td>
                    <td align="center"><?php echo $value["dias_calendario"]?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        <?php
        }
        ?>
    </table>
