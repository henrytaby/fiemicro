
<div style="overflow-y: auto; height: 200px;">

    <div class="FormularioSubtitulo" style="width: 90% !important;"> <i class='fa fa-commenting-o' aria-hidden='true'></i> <?php echo count($region) . ' '. $this->lang->line('titulo_regiones_supervisadas'); ?></div>
    <div class="FormularioSubtituloComentarioNormal" style="width: 90% !important;"> <?php echo $this->lang->line('regionaliza_ayuda'); ?> </div>

    <?php
    // Armar Array
    $i = 0;
    foreach ($region as $key => $value) {
        $item_valor = array(
            "estructura_regional_id" => $value->estructura_regional_id,
            "estructura_regional_nombre" => $value->estructura_regional_nombre . ((int)$value->estructura_regional_estado==1 ? '' : ' (Cerrada)'),
            "estructura_regional_departamento" => ((int)$value->estructura_regional_ciudad==115 ? 'LA PAZ - EL ALTO' : 'Departamento ' . $this->mfunciones_generales->GetValorCatalogoDB($value->estructura_regional_departamento, 'dir_departamento'))
        );
        $lst_resultado[$i] = $item_valor;

        $i++;
    }
    
    // Ordenar Array por departamento
    $arrRegion = $this->mfunciones_generales->ArrGroupBy($lst_resultado, 'estructura_regional_departamento');
    
    $c = 0;
    foreach ($arrRegion as $key => $values)
    {
        echo '<div style="clear: both;"><br /><div style="font-style: italic; font-weight: bold; text-align: left; font-size: 0.85em;"> <i class="fa fa-map-marker" aria-hidden="true"></i> ' . $key . '</div><div style="clear: both;"></div>';
        
        foreach ($values as $value)
        {
            $c++;
            echo '<div class="col-sm-4"><label class="label-campo" >' . $c . '. ' . $value["estructura_regional_nombre"] . '</label></div>';
        }
    }
    
    ?>


    <br /><br />
</div>