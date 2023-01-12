<script type="text/javascript">
    
    $("#boton_mostrar_funnel").fadeIn();
    
    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": 10,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[1, "asc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
    
    $('#divDetalle_filter').css('margin-top','-10px');
    $('#divDetalle_filter').css('margin-bottom','0px');
    $('#divDetalle_filter').css('padding','0px');
    
</script>

<div class="inv-piramide-titulo" style="width: 95%; padding-left: 5%; text-align: center;">
    <span style="font-size: 0.8em;"><?php echo mb_strtoupper($etapa_nombre); ?></span>
</div>

<div style="width: 100%; overflow-x: auto">
    <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 100%;">
        <thead>
        <tr>
            <th style="width: 5%;"> Código Cliente </th>
            <th style="width: 14%;"><?php echo $this->lang->line('general_solicitante_corto'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('general_ci'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('import_campana'); ?></th>
            <th style="width: 14%;"><?php echo $this->lang->line('general_actividad'); ?></th>
            <th style="width: 18%;"><?php echo $this->lang->line('general_destino'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('sol_monto_bs'); ?></th>
            <th style="width: 9%;"><?php echo $this->lang->line('terceros_columna8'); ?></th>
            <th style="width: 10%;">Días Transcurridos</th>
        </tr>
        </thead>
        <?php
        if (isset($arrRespuesta[0])) 
        {
        ?>
            <tbody>
            <?php foreach ($arrRespuesta as $key => $value):?>
                <tr class="FilaBlanca">
                    <td align="center"><?php echo ($value["tipo"]==1 ? PREFIJO_PROSPECTO : 'SOL_') . $value["prospecto_id"]?></td>
                    <td align="center"><?php echo $value["general_solicitante"]; ?></td>
                    <td align="center"><?php echo $value["general_ci"] . ' ' . $value["general_ci_extension"]; ?></td>
                    <td align="center"><?php echo $value["camp_nombre"]?></td>
                    <td align="center"><?php echo $value["general_actividad"]?></td>
                    <td align="center"><?php echo $value["general_destino"]?></td>
                    <td align="center"><?php echo $value["sol_monto_bs"]?></td>
                    <td align="center"><?php echo $value["fecha_registro"]?></td>
                    <td align="center"><?php echo $value["dias_calendario"]?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        <?php
        }
        ?>
    </table>
</div>

<br /><br /><br />
