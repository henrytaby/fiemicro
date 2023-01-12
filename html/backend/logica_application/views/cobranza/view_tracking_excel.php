
<?php 
    $cantidad_columnas = 11; 
	
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
                <?php echo $this->lang->line('seguimiento_Reporte') . $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app'); ?>
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

            <!-- Similar al EXCEL -->

            <th style="width:6%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_codigo'); ?> </th>
            <th style="width:9%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_agencia'); ?> </th>
            <th style="width:9%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_agente'); ?> </th>
            <th style="width:10%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_cliente'); ?> </th>
            <th style="width:10%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_num_proceso'); ?> </th>
            <th style="width:9%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_estado'); ?> </th>
            <th style="width:9%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_fec_registro'); ?> </th>
            <th style="width:9%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_fec_visita'); ?> </th>
            <th style="width:10%; font-weight: bold;">
               <?php echo $this->lang->line('cv_resultado'); ?> </th>
            <th style="width:9%; font-weight: bold;">
               <?php echo $this->lang->line('cv_fecha_compromiso'); ?> </th>
            <th style="width:10%; font-weight: bold;">
               <?php echo $this->lang->line('cv_checkin'); ?> </th>

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

            <!-- Similar al EXCEL -->

            <td style="text-align: center; background-color: #fcfcfc;">
                <?php
                echo $this->lang->line('norm_prefijo') . $value["norm_id"];
                ?>
            </td>

            <td style="text-align: center; background-color: #fcfcfc;">
                <?php echo $value["agencia_nombre"]; ?>
            </td>
            <td style="text-align: center; background-color: #fcfcfc;">
                <?php echo $value["ejecutivo_nombre"]; ?>
            </td>
            <td style="text-align: center; background-color: #fcfcfc;">
                <?php echo $value["cliente_nombre"]; ?>
            </td>
            <td style="text-align: center; background-color: #fcfcfc;">
                <?php echo $value["registro_num_proceso"]; ?>
            </td>
            <td style="text-align: center; background-color: #fcfcfc;">
                <?php echo $value["registro_consolidado"] . ($value["norm_ultimo_paso_check"] ? '<br /><i>¡Registro Pendiente!</i>' : ''); ?>
            </td>
            <td style="text-align: center; background-color: #fcfcfc;">
                <?php echo $value["fecha_registro"]; ?>
            </td>
            <td style="text-align: center;">
                <?php echo $value["cv_fecha"]; ?>
            </td>
            <td style="text-align: center;">
                <?php echo $value["cv_resultado"]; ?>
            </td>
            <td style="text-align: center;">
                <?php echo $value["cv_fecha_compromiso"]; ?>
            </td>

            <td style="text-align: center;">
                <?php 
                    echo $value["cv_checkin_fecha"] . '<br><span style="font-size: 0.8em;">GEO: ' . $value["cv_checkin_geo"] . '</span>';
                ?>
            </td>

            <!-- Similar al EXCEL -->

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