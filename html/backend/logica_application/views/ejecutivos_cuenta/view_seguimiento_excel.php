
<?php 
    $cantidad_columnas = 8; 
	
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=reporte_initium_Seguimiento_" . date('Ymd_His').".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
	
?>

<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
    <thead>
        <tr class="FilaCabecera">

            <th colspan="<?php echo $cantidad_columnas; ?>">
                <?php echo $this->lang->line('NombreSistema_corto'); ?>
                <br />
                <?php echo $this->lang->line('seguimiento_Reporte') . $this->mfunciones_generales->GetValorCatalogo($_SESSION["identificador_tipo_perfil_app_tracking"], 'tipo_perfil_app'); ?>
                <br />
                <?php echo 'Generado por: ' . $_SESSION["session_informacion"]["nombre"] . ' (' . $_SESSION["session_informacion"]["login"] . ')'; ?>
                <br />
                <?php echo 'En fecha: ' . date('d/m/Y H:i:s'); ?>
            </th>

        </tr>

        <tr class="FilaCabecera">

            <th colspan="<?php echo $cantidad_columnas; ?>" style="text-align: left !important; font-weight: normal;">

                <?php

                if(isset($_SESSION['arrResultadoSeguimiento_filtro']))
                {
                    echo $_SESSION['arrResultadoSeguimiento_filtro'];
                } 

                ?>

            </th>
        </tr>

        <tr>
            <td colspan="<?php echo $cantidad_columnas; ?>" style="text-align: left !important;">
                &nbsp;
            </td>
        </tr>

        <tr class="FilaCabecera">

            <th style="width:10%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_cliente'); ?> </strong> </th>
            <th style="width:12%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_agencia'); ?> </strong> </th>
            <th style="width:12%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_oficial'); ?> </strong> </th>
            <th style="width:10%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_rubro'); ?> </strong> </th>
            <th style="width:15%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_soliciante'); ?> </strong> </th>
            <th style="width:15%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_actividad'); ?> </strong> </th>
            <th style="width:12%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_fecreg'); ?> </strong> </th>
            <th style="width:14%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_feccheck'); ?> </strong> </th>

            <!-- Similar al EXCEL -->

        </tr>
    </thead>
    <tbody>
    <?php

    $mostrar = true;
    if (count($_SESSION['arrResultadoSeguimiento'][0]) == 0) 
    {
            $mostrar = false;
    }

    if ($mostrar) 
    {                
    $i=0;
    $strClase = "FilaBlanca";
    foreach ($_SESSION['arrResultadoSeguimiento'] as $key => $value) 
    {                    
        $i++;

        ?> 
        <tr class="<?php echo $strClase; ?>">

            <td style="text-align: center;">

            <?php
            // 1 = Prospecto        2 = Mantenimiento
            if($value["tipo_visita_codigo"] == 1)
            {
                echo PREFIJO_PROSPECTO . $value["codigo"];
            }

            if($value["tipo_visita_codigo"] == 2)
            {
                echo 'SOL_' . $value["codigo"];
            }
            ?>

            </td>

            <td style="text-align: center;">
                <?php echo $value["agencia_nombre"]; ?>
            </td>

            <td style="text-align: center;">
                <?php echo $value["ejecutivo_nombre"]; ?>
            </td>

            <td style="text-align: center;">
                <?php echo $value["codigo_rubro"]; ?>
            </td>

            <td style="text-align: center;">
                <?php echo $value["solicitante"]; ?>
            </td>

            <td style="text-align: center;">
                <?php echo $value["actividad"]; ?>
            </td>

            <td style="text-align: center;">
                <?php echo $value["fecha_registro"]; ?>
            </td>

            <td style="text-align: center;">
                <?php 
                    echo $value["fecha_checkin"] . '<br><span style="font-size: 0.8em;">GEO: ' . $value["checkin_geo"] . '</span>';
                ?>
            </td>

        </tr>
    <?php
    }
    ?>
    </tbody>
    <?php
    $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca";
    //endfor;
    }
    else 
    {
    ?>
    <tr>
        <td style="width: 55%; color: #25728c;" colspan="<?php echo $cantidad_columnas; ?>">
            <br><br>
            <?php echo $this->lang->line('TablaNoRegistros'); ?>
            <br><br>
        </td>                               
    </tr>
<?php } ?>            
</table>