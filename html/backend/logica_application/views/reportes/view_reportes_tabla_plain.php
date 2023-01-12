<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<?php if (!$resultado->error):?>
<?php $columnas_grupo = count($resultado->columnas_grupo);?>
    <?php // Crea las cabeceras de grupos para etapas
    $grupos = array();
    foreach ($resultado->columnas as $columna) {
        $grupos[$columna->grupo] = isset($grupos[$columna->grupo])?$grupos[$columna->grupo]+1:1;
    }
    ?>

    <div style="font-size: 14px; font-family: 'Open Sans', Arial, sans-serif; text-align: center; font-weight: bold;">REPORTE DE SEGUIMIENTO DE CLIENTES</div>

    <br />
    
    <?php

        $aux = json_decode(json_encode($parametros), True);

        $texto_agrupar = '';
        $texto_dato = '';

        switch ($aux['campos_grupo']) {
            case 'prospecto_id':
                $texto_agrupar = 'Sin Agrupar';
                break;
            case 'agencia_id':
                $texto_agrupar = 'Agencia';
                break;
            case 'region_id':
                $texto_agrupar = 'Region';
                break;
            case 'entidad_id':
                $texto_agrupar = 'Entidad';
                break;
            case 'ejecutivo_nombre':
                $texto_agrupar = 'Ejecutivo de Cuenta';
                break;
            case 'empresa_id':
                $texto_agrupar = 'Empresa Aceptante';
                break;
            case 'entidad_id,region_id,agencia_id':
                $texto_agrupar = 'Entidad/Region/Agencia';
                break;

            default:
                $texto_agrupar = 'Invalido';
                break;
        }

        switch ($aux['funcion_mostrar']) {
            case 'total':
                $texto_dato = 'Total Horas';
                break;
            case 'promedio':
                $texto_dato = 'Promedio Horas';
                break;
            case 'registros':
                $texto_dato = 'Clientes Registrados';
                break;

            default:
                $texto_dato = 'Invalido';
                break;
        }

        echo '  <table border="0" cellspacing="0" style="font-family: \'Open Sans\', Arial, sans-serif; font-size: 11px; width: 500px;" align="left">
                    <tr>
                        <td style="width: 250px; font-weight: bold;"> Agrupado Por: ' . $texto_agrupar . ' </td>
                        <td style="width: 250px; font-weight: bold;"> Datos Mostrados: ' . $texto_dato . '</td>
                    </tr>
                </table>
                
                <br />
            ';        
        
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
    
    <?php $aux_prefijo = 0; if ($resultado->tiene_detalles){ $aux_prefijo = 1; }?>
    
    <table align="center" id="tbReporte" class="tblListas Centrado responsive" cellspacing="0" border="1" style="font-size: 10px; font-family: 'Open Sans', Arial, sans-serif; text-align: center;">
    <thead>
    <tr class="FilaCabecera">
        <th colspan="<?php echo count($resultado->columnas_grupo);?>"></th>
        <?php foreach ($grupos as $grupo=>$columnas):?>
            <th colspan="<?php echo $columnas;?>"><?php echo $grupo;?></th>
        <?php endforeach;?>

    </tr>
    <?php $columna_actual = 0;?>
    <tr style="background-color:lightgrey">
        <?php foreach ($resultado->columnas_grupo as $grupo):?>
            <th col="<?php echo $columna_actual++;?>"><?php echo $grupo->titulo;?></th>
        <?php endforeach;?>
        <?php foreach ($resultado->columnas as $columa):?>
            <th col="<?php echo $columna_actual++;?>" style="text-align: center;"><?php echo $columa->titulo;?></th>
        <?php endforeach;?>
    </tr>
    </thead>
    <tbody>
        <?php $funcion_mostrar = $resultado->funcion_mostrar;?>
        <?php foreach ($resultado->filas as $fila):?>
            <tr>
                <?php foreach ($resultado->columnas_grupo as $grupo):?>
                    <?php                    
                    if($aux_prefijo == 1)
                    {                    
                    ?>
                        <td align="center">
                            <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleProspectoReporte('<?php echo $fila->{"campo_grupo_".$grupo->alias_sql}; ?>')">
                                <?php echo $this->mfunciones_generales->ObtenerSolicitanteData($fila->{"campo_grupo_".$grupo->alias_sql}, 'general_solicitante');?>
                            </span>
                        </td>
                    <?php
                    }
                    else
                    {
                    ?>
                        <td align="center"><?php echo $fila->{"campo_grupo_".$grupo->alias_sql};?></td>
                    <?php
                    }
                    ?>
                <?php endforeach;?>
                <?php foreach ($resultado->columnas as $key=>$columa):?>
                    <?php $dato = $funcion_mostrar($fila->etapas, $key);?>
                    <td style="text-align: center;<?php if (!isset($fila->etapas[$key])):?>background-color:#eeeeee<?php endif;?>"><?php echo $dato?>
                        <?php if ($resultado->tiene_detalles && isset($fila->etapas[$key])):?>
                            <?php $dato = $fila->etapas[$key]?>
                        <?php endif;?>
                    </td>
                <?php endforeach;?>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php endif;?>
