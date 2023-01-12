<script type="text/javascript">
<?php
// Si no existe informaci?n, no se mostrar? como tabla con columnas con ?rden
if(count($reporte_soa->array_listado[0]) > 0)
{
?>  
    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": <?php echo PAGINADO_TABLA; ?>,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[12, "asc"]], // Sort by last column ascending,
        "oLanguage": idioma_table
    });
<?php
}
?>    
    
    function Ajax_CargarAccion_Detalle_Log(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('AuditoriaSOA<?php echo ($accion_gestion=='borrado' ? 'clear' : '') ;?>/Detalle', 'divElementoFlotante', "divErrorListaResultado", '', strParametros);
    }
    
    function Exportar(tipo) {
        var w = window.open('about:blank');
        w.document.write('<form method="post" id="formID" action="AuditoriaSOA<?php echo ($accion_gestion=='borrado' ? 'clear' : '') ;?>/Resultado"><span style="font-family: tahoma;">Generando Reporte... ' + (tipo=='excel' ? 'Al completar el proceso puede cerrar esta pestaña.' : '') + '</span><input type="hidden" value="<?php echo $accion_gestion; ?>" name="accion_gestion"/><input type="hidden" value="' + tipo + '" name="tipo"/><input type="hidden" value="<?php echo $fecha_inicio; ?>" name="fecha_inicio"/><input type="hidden" value="<?php echo $fecha_fin; ?>" name="fecha_fin"/><input type="hidden" value="<?php echo $servicio; ?>" name="servicio"/><input type="hidden" value="<?php echo $respuesta; ?>" name="respuesta"/><input type="hidden" value="<?php echo $validacion; ?>" name="validacion"/></form>');
        w.document.getElementById("formID").submit();
        return false;
    }
    
    <?php
    if($accion_gestion == 'borrado')
    {
    ?>
        function Ajax_CargarAccion_Borrado_Log() {
        var strParametros = "&fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>" + "&seleccion=" + $('input[name=opcion_seleccion]:checked').val();;
        Ajax_CargadoGeneralPagina('AuditoriaSOAclear/Proceder', 'divVistaMenuPantalla', "divErrorResultado", '', strParametros);
    }

    <?php
    }
    ?>
    
</script>

<table class="tblListas Centrado" style="width: 100% !important;" border="0">

    <?php $strClase = "FilaGris"; ?>
    <tr class="FilaCabecera">
    
        <th style="width: 40%; text-align: left !important;">
            <strong> &nbsp; TOTAL CANTIDAD DE REGISTROS OBTENIDOS: </strong>
            <?php echo $reporte_soa->total; ?>
        </th>
        
        <th style="width: 60%; text-align: center;">
            <span style="font-weight: bold; font-size: 1.2em;">RESUMEN DEL REPORTE</span>
        </th>
    
    </tr>
    
    <tr  class="<?php echo $strClase; ?>">
        
        <td colspan="2" style="width: 100%; text-align: center;">
            
            <table style="width: 100%; border: 0px;">
                <tr>
                    <td valign="top" style="width: 40%; text-align: left; vertical-align: top !important; padding: 2px 4px !important;">
                        <?php echo $filtro_texto; ?>
                    </td>
                    <td valign="top" style="width: 22%; text-align: left; vertical-align: top !important; padding: 2px 4px !important;">
                        <strong>Por Servicio</strong>
                        <br /> • Validación COBIS-SEGIP: <?php echo $reporte_soa->servicio_segip; ?>
                        <br /> • Prueba de Vida: <?php echo $reporte_soa->servicio_liveness; ?>
                        <br /> • OCR: <?php echo $reporte_soa->servicio_ocr; ?>
                        <br /> • Otros: <?php echo $reporte_soa->servicio_otro; ?>
                    </td>
                    <td valign="top" style="width: 19%; text-align: left; vertical-align: top !important; padding: 2px 4px !important;">
                        <strong>Tipo de Respuesta</strong>
                        <br /> • Exitoso: <?php echo $reporte_soa->respuesta_success; ?>
                        <br /> • Error: <?php echo $reporte_soa->respuesta_error; ?>
                    </td>
                    <td valign="top" style="width: 19%; text-align: left; vertical-align: top !important; padding: 2px 4px !important;">
                        <strong>Tipo de Validación</strong>
                        <br /> • Primer intento: <?php echo $reporte_soa->validacion_primero; ?>
                        <br /> • Reintento: <?php echo $reporte_soa->validacion_reintento; ?>
                    </td>
                </tr>                            
            </table>
            
        </td>
        
    </tr>
    
</table>

<?php $cantidad_columnas = 13;?>

<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 98%;">
	<thead>

            <tr class="FilaCabecera">

                <!-- Similar al EXCEL -->

                <th style="width:9%;"> Token de registro front-end </th>
                <th style="width:8%;"> Número CI </th>
                <th style="width:9%;"> Nombre del Servicio </th>
                <th style="width:8%;"> Fecha y hora de consumo del servicio </th>
                <th style="width:8%;"> Tipo Validación </th>
                <th style="width:8%;"> Tipo Respuesta </th>
                <th style="width:8%;"> Validación Cliente COBIS </th>
                <th style="width:9%;"> Código respuesta SEGIP </th>
                <th style="width:8%;"> Resultado Prueba de Vida </th>
                <th style="width:8%;"> Resultado comparación Selfie vs. Foto SEGIP </th>
                <th style="width:8%;"> Resultado comparación Selfie vs. Foto CI </th>
                <th style="width:8%;"> Porcentaje de coincidencia OCR vs. Registro Cliente </th>

                <!-- Similar al EXCEL -->

                <th style="width:1%;"> <i class="fa fa-eye" aria-hidden="true"></i> </th>
            </tr>
	</thead>
	<tbody>
            <?php

            $mostrar = true;
            if (count($reporte_soa->array_listado[0]) == 0) 
            {
                $mostrar = false;
            }

            if ($mostrar) 
            {                
                $i=0;
                $strClase = "FilaBlanca";
                foreach ($reporte_soa->array_listado as $key => $value) 
                {
                    ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <!-- Similar al EXCEL -->

                        <td style="text-align: center;">
                            <?php echo $value['token']; ?>
                        </td>
                        
                        <td style="text-align: center;">
                            <?php echo $value['nro_ci']; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['servicio_nombre']; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['servicio_fecha']; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['tipo_validacion']; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['tipo_respuesta']; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['cliente_cobis']; ?>
                        </td>
                        
                        <td style="text-align: center;">
                            <?php echo $value['respuesta_segip']; ?>
                        </td>
                        
                        <td style="text-align: center;">
                            <?php echo $value['respuesta_prueba_vida']; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['comparacion_selfie_segip']; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['comparacion_selfie_ci']; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['porcentaje_similaridad']; ?>
                        </td>

                        <!-- Similar al EXCEL -->

                        <td style="text-align: center;">

                            <span class="EnlaceSimple" onclick="Ajax_CargarAccion_Detalle_Log('<?php echo $value["audit_id"]; ?>')">
                                <i class="fa fa-eye" aria-hidden="true"></i><span style="display: none; font-size: 1px;"><?php echo $value['audit_id']; ?></span>
                            </span>

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
                    <div class='PreguntaConfirmar'> No se encontraron registros con los filtros indicados. </div>
                    <br><br>                                           
                </td>                               
            </tr>
	<?php } ?>            
</table>

<br /><br />

<?php
if($accion_gestion == 'seguimiento')
{
?>
    <a id="btnExportarPDF" class="BotonMinimalistaPequeno" style="width:100px!important; padding: 10px 20px !important;" onclick="return Exportar('pdf');"> <?php echo $this->lang->line('reportes_exportar_pdf'); ?> </a>
        &nbsp;&nbsp;
    <a id="btnExportarExcel" class="BotonMinimalistaPequeno" style="width:100px!important; padding: 10px 20px !important;" onclick="return Exportar('excel');"> <?php echo $this->lang->line('reportes_exportar_excel'); ?> </a>

<?php
}
?>
    
<?php
if($accion_gestion == 'borrado')
{
?>
    <div class="Botones2Opciones">
        <a id="btnExportarPDF" class="BotonMinimalistaPequeno" style="width:100px!important; padding: 10px 20px !important;" onclick="return Exportar('pdf');"> <?php echo $this->lang->line('reportes_exportar_pdf'); ?> </a>
            &nbsp;&nbsp;
        <a id="btnExportarExcel" class="BotonMinimalistaPequeno" style="width:100px!important; padding: 10px 20px !important;" onclick="return Exportar('excel');"> <?php echo $this->lang->line('reportes_exportar_excel'); ?> </a>
    </div>

    <div class="Botones2Opciones">
        <a id="btnExportarExcel" class="mensaje_alerta" style="width:100px!important;" onclick="MostrarConfirmación();"> <i class="fa fa-trash-o" aria-hidden="true"></i> Borrar Registros </a>
    </div>

<?php
}
?>  