<script type="text/javascript">
<?php
// Si no existe informaci?n, no se mostrar? como tabla con columnas con ?rden
if(count($reporte_ad->array_listado) > 0)
{
?>  
    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": <?php echo PAGINADO_TABLA; ?>,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[0, "asc"]], // Sort by last column ascending,
        "oLanguage": idioma_table
    });
<?php
}
?>    
    
    function Ajax_CargarAccion_Detalle_Log(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('AuditoriaAD/Detalle', 'divElementoFlotante', "divErrorListaResultado", '', strParametros);
    }
    
    function Exportar(tipo) {
        var w = window.open('about:blank');
        w.document.write('<form method="post" id="formID" action="AuditoriaAD/Resultado"><span style="font-family: tahoma;">Generando Reporte... ' + (tipo=='excel' ? 'Al completar el proceso puede cerrar esta pestaña.' : '') + '</span><input type="hidden" value="<?php echo $accion_gestion; ?>" name="accion_gestion"/><input type="hidden" value="' + tipo + '" name="tipo"/><input type="hidden" value="<?php echo $fecha_inicio; ?>" name="fecha_inicio"/><input type="hidden" value="<?php echo $fecha_fin; ?>" name="fecha_fin"/></form>');
        w.document.getElementById("formID").submit();
        return false;
    }
    
</script>

<table class="tblListas Centrado" style="width: 100% !important;" border="0">

    <?php $strClase = "FilaGris"; ?>
    <tr class="FilaCabecera">
    
        <th style="width: 40%; text-align: left !important;">
            <strong> &nbsp; <?php echo $this->lang->line('text_total_ad') ?> </strong>
            <?php echo $reporte_ad->total; ?>
        </th>
        
        <th style="width: 60%; text-align: center;">
            <span style="font-weight: bold; font-size: 1.2em;"><?php echo $this->lang->line('text_res_ad') ?></span>
        </th>
    
    </tr>
    
    <tr  class="<?php echo $strClase; ?>">
        
        <td colspan="2" style="width: 100%; text-align: center;">
            
            <table style="width: 100%; border: 0px;">
                <tr>
                    <td valign="top" style="width: 40%; text-align: left; vertical-align: top !important; padding: 2px 4px !important;">
                        <?php echo $filtro_texto; ?>
                    </td>
                </tr>                            
            </table>
            
        </td>
        
    </tr>
    
</table>

<?php $cantidad_columnas = 7;?>

<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 98%;">
	<thead>

            <tr class="FilaCabecera">

                <!-- Similar al EXCEL -->

                <th style="width: 10%"> <?php echo $this->lang->line('id_table_ad') ?> </th>
                <th style="width: 29%"> <?php echo $this->lang->line('params_table_ad') ?> </th>
                <th style="width: 10%"> <?php echo $this->lang->line('cod_err_table_ad') ?> </th>
                <th style="width: 20%"> <?php echo $this->lang->line('message_table_ad') ?> </th>
                <th style="width: 15%"> <?php echo $this->lang->line('fec_sol_table_ad') ?> </th>
                <th style="width: 15%"> <?php echo $this->lang->line('ip_table_ad') ?> </th> 

                <!-- Similar al EXCEL -->

                <th style="width:1%;"> <i class="fa fa-eye" aria-hidden="true"></i> </th>
            </tr>
	</thead>
	<tbody>
            <?php

            

            $mostrar = true;
            if (count($reporte_ad->array_listado[0]) == 0) 
            {
                $mostrar = false;
            }

            

            if ($mostrar) 
            {                
                $i=0;
                $strClase = "FilaBlanca";
                foreach ($reporte_ad->array_listado as $key => $value) 
                {
                    ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <!-- Similar al EXCEL -->

                        <td style="text-align: center;">
                            <?php echo $key+1; ?>
                        </td>
                        
                        <td style="text-align: center;">
                            <?php echo json_decode($value['auditoria_params'],true); ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['auditoria_cod_error']; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['auditoria_mensaje']; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['auditoria_fecha']; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['auditoria_ip']; ?>
                        </td>

                        <!-- Similar al EXCEL -->

                        <td style="text-align: center;">

                            <span class="EnlaceSimple" onclick="Ajax_CargarAccion_Detalle_Log('<?php echo $value["auditoria_id"]; ?>')">
                                <i class="fa fa-eye" aria-hidden="true"></i><span style="display: none; font-size: 1px;"><?php echo $value['auditoria_id']; ?></span>
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
                    <div class='PreguntaConfirmar'> <?php echo $this->lang->line('text_notfound_ad') ?>  </div>
                    <br><br>                                           
                </td>                               
            </tr>
	<?php } ?>            
</table>

<br /><br />


    <a id="btnExportarPDF" class="BotonMinimalistaPequeno" style="width:100px!important; padding: 10px 20px !important;" onclick="return Exportar('pdf');"> <?php echo $this->lang->line('reportes_exportar_pdf'); ?> </a>
        &nbsp;&nbsp;
    <a id="btnExportarExcel" class="BotonMinimalistaPequeno" style="width:100px!important; padding: 10px 20px !important;" onclick="return Exportar('excel');"> <?php echo $this->lang->line('reportes_exportar_excel'); ?> </a>