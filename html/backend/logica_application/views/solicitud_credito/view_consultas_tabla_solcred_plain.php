<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<div style="font-size: 14px; font-family: 'Open Sans', Arial, sans-serif; text-align: center; font-weight: bold;">REPORTE CONSULTA SOLICITUD DE CRÉDITO <?php echo '<br /> <span style="font-size: 11px; font-style: italic;"> Listando ' . number_format($resultado->cuenta, 0, ',', '.') . ' registro(s) </span>'; ?> </div>

<br /> 

<?php

    $aux = json_decode(json_encode($parametros), True);
    
    if(isset($aux['filtros']) && count($aux['filtros']) > 0)
    {
        echo '  <table align="center" id="divDetalle" class="tblListas Centrado " cellspacing="0" cellpadding="5" border="1" style="width: 100%; font-size: 9px; font-family: \'Open Sans\', Arial, sans-serif; text-align: justify;">
                    <tr>
                        <td colspan="10">';

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

    <table align="center" id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 100%; font-size: 10px; font-family: 'Open Sans', Arial, sans-serif; text-align: center;">
        <thead>
        <tr>
            <th style="width: 7%;"> Código Solicitud </th>
            <th style="width: 9%;"><?php echo $this->lang->line('terceros_columna1'); ?></th>
            <th style="width: 9%;"><?php echo $this->lang->line('import_agente'); ?></th>
            <th style="width: 9%;"><?php echo $this->lang->line('sol_ci'); ?></th>
            <th style="width: 8%;"><?php echo $this->lang->line('import_campana'); ?></th>
            <th style="width: 9%;"><?php echo $this->lang->line('sol_nombre_completo'); ?></th>
            <th style="width: 9%;"><?php echo $this->lang->line('terceros_columna5'); ?></th>
            <th style="width: 8%;"><?php echo $this->lang->line('sol_monto'); ?></th>
            <th style="width: 14%;"><?php echo $this->lang->line('sol_detalle'); ?></th>
            <th style="width: 9%;"><?php echo $this->lang->line('terceros_columna8'); ?></th>
            <th style="width: 9%;"><?php echo $this->lang->line('terceros_columna7'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($resultado->filas as $fila):?>
            <tr class="FilaBlanca">
                <td align="center">SOL_<?php echo $fila->sol_id?></td>
                <td align="center"><?php echo $fila->codigo_agencia_fie; ?></td>
                <td align="center"><?php echo $fila->ejecutivo_nombre; ?></td>
                <td align="center"><?php echo $fila->sol_ci?></td>
                <td align="center"><?php echo $fila->sol_codigo_rubro?></td>
                <td align="center"><?php echo $fila->sol_nombre_completo?></td>
                <td align="center"><?php echo $fila->sol_correo . (str_replace(' ', '', $fila->sol_correo)=='' ? '' : '<br />') . $fila->sol_cel ?></td>
                <td align="center"><?php echo $fila->sol_monto?></td>
                <td align="center"><?php echo $fila->sol_detalle?></td>
                <td align="center"><?php echo $fila->sol_fecha?></td>
                <td align="center"><?php echo $fila->sol_estado . $fila->sol_estado_aux?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
