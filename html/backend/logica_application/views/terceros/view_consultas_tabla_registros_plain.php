<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<div style="font-size: 14px; font-family: 'Open Sans', Arial, sans-serif; text-align: center; font-weight: bold;">REPORTE CONSULTA REGISTROS ONBOARDING <?php echo '<br /> <span style="font-size: 11px; font-style: italic;"> Listando ' . number_format($resultado->cuenta, 0, ',', '.') . ' registro(s) </span>'; ?> </div>

<br /> 

<?php

    $aux = json_decode(json_encode($parametros), True);
    
    if(isset($aux['filtros']) && count($aux['filtros']) > 0)
    {
        echo '<div style="width: 900px; font-size: 10px; font-family: \'Open Sans\', Arial, sans-serif; text-align: justify; padding: 5px; border: 2px solid #000000;">';

        foreach ($aux['filtros'] as $key => $value) 
        {
            echo '<strong>*' . $value['titulo'] . '</strong> ' . $value['descripcion'] . '<br />';
        }

        echo '</div> <br />';
    }

?>

    <table align="center" id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%; font-size: 10px; font-family: 'Open Sans', Arial, sans-serif; text-align: center;">
        <thead>
        <tr>
            <th style="width: 13%;">Código Cliente</th>
            <th style="width: 12%;"><?php echo $this->lang->line('terceros_columna1'); ?></th>
            <th style="width: 12%;"><?php echo $this->lang->line('terceros_columna2'); ?></th>
            <th style="width: 13%;"><?php echo $this->lang->line('terceros_columna3'); ?></th>
            <th style="width: 13%;"><?php echo $this->lang->line('terceros_columna4'); ?></th>
            <th style="width: 13%;"><?php echo $this->lang->line('terceros_columna5'); ?></th>
            <th style="width: 12%;"><?php echo $this->lang->line('terceros_columna7'); ?></th>
            <th style="width: 12%;"><?php echo $this->lang->line('terceros_columna8'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($resultado->filas as $fila):?>
            <tr class="FilaBlanca">
                <td align="center"><?php echo PREFIJO_TERCEROS . $fila->terceros_id . '<br /><span style="font-size: 0.9em; font-style: italic;">' . $fila->tercero_asistencia . '<br />' . $fila->tipo_cuenta . '</span>'; ?></td>
                <td align="center"><?php echo $fila->terceros_columna1; ?></td>
                <td align="center"><?php echo $fila->terceros_columna2; ?></td>
                <td align="center"><?php echo $fila->terceros_columna3; ?></td>
                <td align="center"><?php echo $fila->terceros_columna4; ?></td>
                <td align="center"><?php echo $fila->terceros_columna5; ?></td>
                <td align="center"><?php echo $fila->terceros_columna7; ?></td>
                <td align="center"><?php echo $fila->terceros_columna8; ?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
