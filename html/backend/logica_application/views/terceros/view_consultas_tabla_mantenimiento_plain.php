<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<div style="font-size: 14px; font-family: 'Open Sans', Arial, sans-serif; text-align: center; font-weight: bold;">REPORTE CONSULTA MANTENIMIENTOS</div>

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
            <th style="width: 10%;">Código Mantenimiento</th>
            <th style="width: 20%;"><?php echo $this->lang->line('empresa_nombre'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('empresa_categoria'); ?></th>
            <th style="width: 15%;"><?php echo $this->lang->line('empresa_ejecutivo'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('mant_fecha_asignacion'); ?></th>
            <th style="width: 15%;"><?php echo $this->lang->line('solicitud_estado'); ?></th>
            <th style="width: 20%;"><?php echo $this->lang->line('mant_tareas_realizadas'); ?></th>            
        </tr>
        </thead>
        <tbody>
        <?php foreach ($resultado->filas as $fila):?>
            <tr class="FilaBlanca">
                <td align="center"><?php echo PREFIJO_MANTENIMIENTO . $fila->mant_id?></td>
                <td align="center"><?php echo $fila->empresa_nombre?></td>
                <td align="center"><?php echo $fila->empresa_categoria?></td>
                <td align="center"><?php echo $fila->ejecutivo_nombre?></td>
                <td align="center"><?php echo $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($fila->mant_fecha_asignacion)?></td>
                <td align="center"><?php echo $this->mfunciones_generales->GetValorCatalogo($fila->mant_estado, 'estado_mantenimiento');?></td>
                
                
                <td align="justify">
                    
                    <?php
                    
                        // Listado de Servicios asignados a la Solicitud
                        $arrTareas = $this->mfunciones_logica->ObtenerDetalleMantenimiento_tareas($fila->mant_id);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTareas);

                        if(isset($arrTareas[0]))
                        {
                            foreach ($arrTareas as $key => $value) 
                            {
                                echo ' - ' . $value["tarea_detalle"] . '<br />';
                            }                                
                        }
                        else
                        {
                            echo $this->lang->line('consulta_listado_pendiente');
                        }
                    
                    ?>
                    
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
