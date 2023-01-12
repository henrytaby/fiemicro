<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<div style="font-size: 14px; font-family: 'Open Sans', Arial, sans-serif; text-align: center; font-weight: bold;">REPORTE CONSULTA CLIENTES <?php echo '<br /> <span style="font-size: 11px; font-style: italic;"> Listando ' . number_format($resultado->cuenta, 0, ',', '.') . ' registro(s) </span>'; ?> </div>

<br /> 

<?php

    $aux = json_decode(json_encode($parametros), True);
    
    if(isset($aux['filtros']) && count($aux['filtros']) > 0)
    {
        echo '  <table align="center" id="divDetalle" class="tblListas Centrado " cellspacing="0" cellpadding="5" border="1" style="width: 100%; font-size: 9px; font-family: \'Open Sans\', Arial, sans-serif; text-align: justify;">
                    <tr>
                        <td colspan="12">';

                        foreach ($aux['filtros'] as $key => $value) 
                        {
                            echo '<strong>*' . $value['titulo'] . '</strong> ' . $value['descripcion'] . '<br />';
                        }

        echo '          </td>
                    </tr>
                </table><br />
            ';
    }

?>

    <table align="center" id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 100%; font-size: 9px; font-family: 'Open Sans', Arial, sans-serif; text-align: center;">
        <thead>
        <tr>
            <th style="width: 5%;"> Código Cliente </th>
            <th style="width: 8%;"><?php echo $this->lang->line('terceros_columna1'); ?></th>
            <th style="width: 9%;"><?php echo $this->lang->line('general_solicitante_corto'); ?></th>
            <th style="width: 8%;"><?php echo $this->lang->line('general_ci'); ?></th>
            <th style="width: 8%;"><?php echo $this->lang->line('import_campana'); ?></th>
            <th style="width: 8%;"><?php echo $this->lang->line('import_agente'); ?></th>
            <th style="width: 8%;"><?php echo $this->lang->line('prospecto_etapa_actual'); ?></th>
            <th style="width: 9%;"><?php echo $this->lang->line('general_actividad'); ?></th>
            <th style="width: 13%;"><?php echo $this->lang->line('general_destino'); ?></th>
            <th style="width: 8%;"><?php echo $this->lang->line('prospecto_actividades'); ?></th>
            <th style="width: 8%;"><?php echo $this->lang->line('prospecto_jda_eval'); ?></th>
            <th style="width: 8%;"><?php echo $this->lang->line('sol_monto_bs'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($resultado->filas as $fila):?>
            <tr class="FilaBlanca">
                <td align="center"><?php echo PREFIJO_PROSPECTO . $fila->prospecto_id?></td>
                <td align="center"><?php echo $fila->estudio_agencia_nombre; ?></td>
                <td align="center"><?php echo $fila->general_solicitante; ?></td>
                <td align="center"><?php echo $fila->general_ci . ' ' . $this->mfunciones_generales->GetValorCatalogo($fila->general_ci_extension, 'extension_ci'); ?></td>
                <td align="center"><?php echo $fila->camp_nombre?></td>
                <td align="center"><?php echo $fila->ejecutivo_nombre?></td>
                <td align="center"><?php echo $fila->etapa_nombre?></td>
                <td align="center"><?php echo $fila->general_actividad?></td>
                <td align="center"><?php echo $fila->general_destino?></td>
                <td align="justify"><?php echo $this->mfunciones_generales->GetValorCatalogo($fila->prospecto_id, 'lead_actividades_plain'); ?></td>
                <td align="center"><?php echo $this->mfunciones_generales->GetValorCatalogo($fila->prospecto_jda_eval, 'prospecto_evaluacion'); ?></td>
                <td align="center"><?php echo $fila->sol_monto_bs?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
