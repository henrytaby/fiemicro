<script type="text/javascript">
    function Exportar(tipo) {
        var dataObj = <?php echo json_encode($parametros)?>;
        dataObj.exportar = tipo;
        var data = encodeURI(JSON.stringify(dataObj));
        var w = window.open('about:blank');
        w.document.write('<form method="post" id="formID" action="Consultas/Generar">Generando Reporte...<input type="hidden" value="' + data + '" name="parametros"/></form>');
        w.document.getElementById("formID").submit();
        return false;
    }
    
    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": <?php echo PAGINADO_TABLA + 5; ?>,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[1, "asc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
    
    function Ajax_CargarAccion_DetalleMantenimientoCon(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Mantenimiento/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
</script>
<div style="width: 100%; overflow-x: auto">
    <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
        <thead>
        <tr>
            <th style="width: 10%;">Código Mantenimiento</th>
            <th style="width: 15%;"><?php echo $this->lang->line('empresa_nombre'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('empresa_categoria'); ?></th>
            <th style="width: 15%;"><?php echo $this->lang->line('empresa_ejecutivo'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('mant_fecha_asignacion'); ?></th>
            <th style="width: 10%;"><?php echo $this->lang->line('solicitud_estado'); ?></th>
            <th style="width: 15%;"><?php echo $this->lang->line('mant_tareas_realizadas'); ?></th>
            <th style="width: 15%;">Opciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($resultado->filas as $fila):?>
            <tr class="FilaBlanca">
                <td align="center">
                    <?php echo PREFIJO_MANTENIMIENTO . $fila->mant_id?>
                </td>
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
                
                
                <td align="center">
                    
                    <span class="BotonSimple" onclick="Ajax_CargarAccion_DetalleMantenimientoCon('<?php echo $fila->mant_id; ?>')">
                        <?php echo $this->lang->line('consulta_opciones'); ?>
                    </span>
                    
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<br /><br />

<a id="btnExportarPDF" class="BotonMinimalistaPequeno" style="width:100px!important;" onclick="return Exportar('pdf');"> <?php echo $this->lang->line('reportes_exportar_pdf'); ?> </a>
&nbsp;&nbsp;
<a id="btnExportarExcel" class="BotonMinimalistaPequeno" style="width:100px!important;" onclick="return Exportar('excel');"> <?php echo $this->lang->line('reportes_exportar_excel'); ?> </a>
