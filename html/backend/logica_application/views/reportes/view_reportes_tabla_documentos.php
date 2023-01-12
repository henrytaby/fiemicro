<script type="text/javascript">
    function Exportar(tipo) {
        var dataObj = <?php echo json_encode($parametros)?>;
        dataObj.exportar = tipo;
        var data = encodeURI(JSON.stringify(dataObj));
        var w = window.open('about:blank');
        w.document.write('<form method="post" id="formID" action="Reportes/Generar">Generando Reporte...<input type="hidden" value="' + data + '" name="parametros"/></form>');
        w.document.getElementById("formID").submit();
        return false;
    }
    
    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": <?php echo PAGINADO_TABLA+5; ?>,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[0, "desc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
    
</script>
<div style="width: 100%; overflow-x: auto">
    <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
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
</div>

<br /><br />

<a id="btnExportarPDF" class="BotonMinimalistaPequeno" style="width:100px!important;" onclick="return Exportar('pdf');"> <?php echo $this->lang->line('reportes_exportar_pdf'); ?> </a>
&nbsp;&nbsp;
<a id="btnExportarExcel" class="BotonMinimalistaPequeno" style="width:100px!important;" onclick="return Exportar('excel');"> <?php echo $this->lang->line('reportes_exportar_excel'); ?> </a>
