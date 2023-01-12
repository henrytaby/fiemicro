<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<div style="font-size: 14px; font-family: 'Open Sans', Arial, sans-serif; text-align: center; font-weight: bold;">REPORTE DE DOCUMENTOS OBSERVADOS EN LOS CLIENTES</div>

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

    <table align="center" id="tbReporte" class="tblListas Centrado responsive" cellspacing="0" border="1" style="font-size: 10px; font-family: 'Open Sans', Arial, sans-serif; text-align: center;">
        <thead>
        <tr>
            <th style="width: 10%;">Cantidad de Observaciones</th>
            <th style="width: 30%;">Nombre del Documento</th>
            <th style="width: 20%;">Ejecutivo de Cuentas</th>
            <th style="width: 20%;">Sucursal</th>
            <th style="width: 20%;">Fecha Reciente Observación</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($resultado->filas as $fila):?>
            <tr class="FilaBlanca">
                <td align="center"><?php echo $fila->observacion_cantidad?></td>
                <td align="center"><?php echo $fila->documento_nombre?></td>
                <td align="center"><?php echo $fila->ejecutivo_nombre?></td>
                <td align="center"><?php echo $fila->estructura_regional_nombre?></td>
                <td align="center"><?php echo $this->mfunciones_generales->getFormatoFechaD_M_Y($fila->observacion_fecha)?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
