
<div style="text-align: right; width: 95%; font-style: italic;">
    <?php echo ($f_cobis_flujo==1 ? '(Actualmente en el flujo) &nbsp;&nbsp;' : ''); ?>
    <span style="font-weight: bold;" class="EnlaceSimple" onclick="RefreshTrackingFlujoCOBIS(<?php echo $terceros_id; ?>);">
        <i class="fa fa-refresh" aria-hidden="true"></i> Actualizar
    </span>
</div>
<?php echo $this->mfunciones_generales->getTablaTrackingFlujoCOBIS($f_cobis_tracking); ?>