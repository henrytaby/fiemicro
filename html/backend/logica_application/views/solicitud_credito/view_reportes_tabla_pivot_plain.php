
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<?php
    if($parametros->exportar == "pdf")
    {
        echo '<link rel="stylesheet" type="text/css" href="html_public/css/pivot.css">';
    }
?>

<div style="font-size: 14px; font-family: 'Open Sans', Arial, sans-serif; text-align: center; font-weight: bold;">REPORTE CONSTRUCTOR SOLICITUD DE CRÉDITO</div>

<br /> 

<?php

    echo "
        <table class='tblListas Centrado responsive' style='width: 100%; font-size: 12px; font-family: Arial, sans-serif; text-align: justify; padding: 5px;'>
            
            <tr>
                <td style='width: 30%;'>
                    <strong>Agregador Pivot:</strong>
                </td>
                
                <td style='width: 70%;'>
                    " . $parametros->criterio_pivot . "
                </td>
            </tr>

        </table> <br />";

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
    
    $aux_tabla = str_replace('<table ', '<table align="center" style="font-family: Arial, sans-serif; font-size: 10px !important;" ', $parametros->tabla_pivot);
    $aux_tabla = str_replace('<th ', '<th style="font-size: 10px !important;" ', $aux_tabla);
    
    echo $aux_tabla;
    
?>