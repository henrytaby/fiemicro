<script type="text/javascript">
    
    function Costos_Nuevo(estructura_id, codigo_detalle) {
        var strParametros = "&estructura_id=" + estructura_id + "&codigo_detalle=" + codigo_detalle;
        Ajax_CargadoGeneralPagina('../Costos/Form', 'divElementoFlotante', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
    
    function EliminarCostos(estructura_id, producto_id) {
        var strParametros = "&estructura_id=" + estructura_id + "&producto_id=" + producto_id;
        Ajax_CargadoGeneralPagina('../Costos/Eliminar', 'Margen_Utilidad', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
</script>

<div class="row">

    <div class="col" style="text-align: left;">
        <span class="EnlaceSimple label-campo" onclick="MostrarTablaMargen();">
            <strong> <i class="fa fa-reply" aria-hidden="true"></i> REGRESAR </strong>
        </span>
    </div>

    <div class="col" style="text-align: right;">
        <span class="EnlaceSimple label-campo" onclick="Costos_Nuevo('<?php echo $estructura_id; ?>', '-1');">
            <strong> <i class="fa fa-plus-square" aria-hidden="true"></i> NUEVO REGISTRO </strong>
        </span>
    </div>

</div>

<div class="row">

    <div class="col" style="text-align: center;">
        <label class='label-campo texto-centrado panel-heading' for=''> <i class="fa fa-cubes" aria-hidden="true"></i> <?php echo $producto_nombre; ?> <br /> (detalle costos)</label>
    </div>

</div>
        
<?php

$mostrar = false;

$total_costo = 0;

if(count($arrRespuesta[0]) > 0)
{
    $mostrar = true;
?>

    <div style="overflow-x: auto; padding: 0px 10px;">
    
        <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

            <tr class="FilaGris">

                <td style="width: 5%; font-weight: bold; text-align: center;">
                    Opc.
                </td>

                <td style="width: 15%; font-weight: bold; text-align: center;">
                    <?php echo $this->lang->line('detalle_descripcion'); ?>
                </td>

                <td style="width: 10%; font-weight: bold; text-align: center;">
                    <?php echo $this->lang->line('detalle_cantidad'); ?>
                </td>

                <td style="width: 10%; font-weight: bold; text-align: center;">
                    <?php echo $this->lang->line('detalle_unidad'); ?>
                </td>

                <td style="width: 10%; font-weight: bold; text-align: center; text-decoration: underline;">
                    <?php echo $this->lang->line('detalle_costo_unitario'); ?>
                </td>
                
                <td style="width: 10%; font-weight: bold; text-align: center;">
                    <?php echo $this->lang->line('detalle_costo_medida_convertir'); ?>
                </td>
                
                <td style="width: 10%; font-weight: bold; text-align: center;">
                    <?php echo $this->lang->line('detalle_costo_medida_unidad'); ?>
                </td>
                <td style="width: 10%; font-weight: bold; text-align: center;">
                    <?php echo $this->lang->line('detalle_costo_medida_precio'); ?>
                </td>
                
                <td style="width: 10%; font-weight: bold; text-align: center;">
                    <?php echo $this->lang->line('detalle_costo_unidad_medida_uso'); ?>
                </td>
                <td style="width: 10%; font-weight: bold; text-align: center;">
                    <?php echo $this->lang->line('detalle_costo_unidad_medida_cantidad'); ?>
                </td>

            </tr>

            <?php

            if ($mostrar) 
            {   
                $strClase = "FilaBlanca";
                foreach ($arrRespuesta as $key => $value) 
                {
                    $total_costo += $value["detalle_cantidad"] * $value["detalle_costo_unitario"]; 
                    
                    if($value["detalle_costo_medida_convertir_codigo"] == 0)
                    {
                        $clase_auxiliar = "";
                    }
                    else
                    {
                        $clase_auxiliar = 'class="FilaSeleccion"';
                    }
            ?>
                    <tr class="FilaBlanca">

                        <td style="width: 5%; font-weight: bold; text-align: center;">
                            <span class="EnlaceSimple label-campo" onclick="Costos_Nuevo(<?php echo $value["producto_id"]; ?>, <?php echo $value["detalle_id"]; ?>);">
                                <strong> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </strong>
                            </span>
                        </td>

                        <td style="width: 15%; font-weight: normal; text-align: center;">
                            <?php echo $value["detalle_descripcion"]; ?>
                        </td>

                        <td style="width: 10%; font-weight: normal; text-align: center; font-weight: bold;">
                            <?php echo number_format($value["detalle_cantidad"], 2, ',', '.'); ?>
                        </td>

                        <td style="width: 10%; font-weight: normal; text-align: center; font-weight: bold;">
                            <?php echo $value["detalle_unidad"]; ?>
                        </td>

                        <td <?php echo $clase_auxiliar; ?> style="width: 10%; font-weight: normal; text-align: center;">
                            <?php echo number_format($value["detalle_costo_unitario"], 2, ',', '.'); ?>
                        </td>

                        <td <?php echo $clase_auxiliar; ?> style="width: 10%; font-weight: normal; text-align: center;">
                            <?php echo $value["detalle_costo_medida_convertir"]; ?>
                        </td>
                        
                        <td style="width: 10%; font-weight: normal; text-align: center;">
                            <?php echo $value["detalle_costo_medida_unidad"]; ?>
                        </td>
                        <td style="width: 10%; font-weight: normal; text-align: center;">
                            <?php echo number_format($value["detalle_costo_medida_precio"], 2, ',', '.'); ?>
                        </td>
                        
                        <td style="width: 10%; font-weight: normal; text-align: center;">
                            <?php echo $value["detalle_costo_unidad_medida_uso"]; ?>
                        </td>
                        
                        <td style="width: 10%; font-weight: normal; text-align: center;">
                            <?php echo number_format($value["detalle_costo_unidad_medida_cantidad"], 2, ',', '.'); ?>
                        </td>

                    </tr>

            <?php
                }
            }
            ?>

        </table>

    </div>
    <br /><br />

<?php

}

else
{            
    echo "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> Aún No se Registró Información <br /> Puede crear desde NUEVO REGISTRO </div>";
}

?>

    <br />
    
    <div>

        <div class="row">
            <div class="col" style="text-align: center;">
                <label class='texto-centrado' for=''><?php echo $this->lang->line('producto_costo_medida_unidad'); ?></label>
            </div>
            <div class="col" style="text-align: center;">
                <label class='label-campo texto-centrado' for=''><?php echo $producto_costo_medida_unidad; ?></label>
            </div>
        </div>
        
        <div class="row">
            <div class="col" style="text-align: center;">
                <label class='texto-centrado' for=''><?php echo $this->lang->line('producto_costo_medida_cantidad'); ?></label>
            </div>
            <div class="col" style="text-align: center;">
                <label class='label-campo texto-centrado' for=''><?php echo number_format($producto_costo_medida_cantidad, 2, ',', '.'); ?></label>
            </div>
        </div>
        
        <div class="row">
            <div class="col" style="text-align: center;">
                <label class='texto-centrado' for=''><?php echo $this->lang->line('producto_costo_medida_precio'); ?></label>
            </div>
            <div class="col" style="text-align: center;">
                <label class='label-campo texto-centrado' for=''><?php echo number_format($producto_costo_medida_precio, 2, ',', '.'); ?></label>
            </div>
        </div>
        
        <div class="row">
            <div class="col" style="text-align: center;">
                <label class='texto-centrado' for=''><?php echo $this->lang->line('detalle_costo_total'); ?></label>
            </div>
            <div class="col" style="text-align: center;">
                <label class='label-campo texto-centrado' for=''><?php echo number_format($total_costo, 2, ',', '.'); ?></label>
            </div>
        </div>
        
        <br /><br />
        
        <div class="row">
            <div class="col" style="text-align: center;">
                <label class='texto-centrado' for=''><?php echo $this->lang->line('detalle_costo_unitario'); ?></label>
            </div>
            <div class="col" style="text-align: center;">
                <label class='label-campo texto-centrado' for=''>
                    <?php echo number_format(((int)$producto_costo_medida_cantidad!=0 ? $total_costo/$producto_costo_medida_cantidad : 0), 2, ',', '.'); ?></label>
            </div>
        </div>
        
        <div class="row">
            <div class="col" style="text-align: center;">
                <label class='texto-centrado' for=''><?php echo $this->lang->line('detalle_costo_mub'); ?></label>
            </div>
            <div class="col" style="text-align: center;">
                <label class='label-campo texto-centrado' for=''><?php echo number_format(((int)$producto_costo_medida_cantidad!=0 && $producto_costo_medida_precio!=0 ? 1-(($total_costo/$producto_costo_medida_cantidad)/$producto_costo_medida_precio) : 0)*100, 2, ',', '.'); ?>%</label>
            </div>
        </div>
        
    </div>