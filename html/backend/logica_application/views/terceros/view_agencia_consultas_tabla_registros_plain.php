<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<div style="font-size: 14px; font-family: 'Open Sans', Arial, sans-serif; text-align: center; font-weight: bold;">Rerporte <?php echo $this->lang->line('AgenciaTercerosTitulo') . ' - ' . $this->lang->line('bandeja_agencia_atendidas'); ?> <?php echo '<br /> <span style="font-size: 11px; font-style: italic;"> Listando ' . number_format($resultado->cuenta, 0, ',', '.') . ' registro(s) </span>'; ?> </div>

<br /> 

<?php

    $aux = json_decode(json_encode($parametros), True);
    
    if(isset($aux['filtros']) && count($aux['filtros']) > 0)
    {
        echo '<div style="width: 100%; font-size: 10px; font-family: \'Open Sans\', Arial, sans-serif; text-align: justify; padding: 5px; border: 2px solid #000000;">';

        foreach ($aux['filtros'] as $key => $value) 
        {
            echo '<strong>*' . $value['titulo'] . '</strong> ' . $value['descripcion'] . '<br />';
        }

        echo '</div> <br />';
    }

?>

    <table align="center" id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 100%; font-size: 10px; font-family: 'Open Sans', Arial, sans-serif; text-align: center;">
        <thead>
        <tr>
            <th style="width: 12%;"><?php echo $this->lang->line('agencia_r_fecha_proceso'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('agencia_r_fecha_actualizacion'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('agencia_r_cuenta_cobis'); ?></th>
            <th style="width: 7%;"><?php echo $this->lang->line('agencia_r_codigo'); ?></th>
            <th style="width: 13%;"><?php echo $this->lang->line('agencia_r_ci'); ?></th>
            <th style="width: 11%;"><?php echo $this->lang->line('agencia_r_solicitante'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('agencia_r_agencia'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('agencia_r_usuario'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('agencia_r_estado'); ?></th>
            <th style="width: 7%;"><?php echo $this->lang->line('agencia_r_dias_notificacion'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($resultado->filas as $fila):?>
            <tr class="FilaBlanca">
                <td align="center"><?php echo $fila->agencia_r_fecha_proceso; ?></td>
                <td align="center"><?php echo $fila->agencia_r_fecha_actualizacion; ?></td>
                <td align="center"><?php echo $fila->agencia_r_cuenta_cobis; ?></td>
                <td align="center"><?php echo PREFIJO_TERCEROS . $fila->terceros_id; ?></td>
                <td align="center"><?php echo $fila->agencia_r_ci; ?></td>
                <td align="center"><?php echo $fila->agencia_r_solicitante; ?></td>
                <td align="center"><?php echo $fila->agencia_r_agencia; ?></td>
                <td align="center"><?php echo $fila->agencia_r_usuario; ?></td>
                <td align="center"><?php echo $fila->agencia_r_estado; ?></td>
                <td align="center"><?php echo $fila->agencia_r_dias_notificacion; ?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
