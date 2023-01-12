<script type="text/javascript">
<?php
// Si no existe informaci?n, no se mostrar? como tabla con columnas con ?rden
if(count($arrRespuesta[0]) > 0)
{
?>  
    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": 1000,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[0, "asc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
<?php
}
?>    
    function Ajax_CargarAccion_BandejaSolicitud(estado) {
        var strParametros = "&estado=" + estado + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Solicitud/Afiliacion/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleSolicitud(codigo, estado) {
        var strParametros = "&codigo=" + codigo + "&estado=" + estado + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Solicitud/Afiliacion/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    <?php
    if($estado == 0)
    {
    ?>    
        function Ajax_CargarAccion_RechazarSolicitud(codigo, estado) {
            var strParametros = "&codigo=" + codigo + "&estado=" + estado + "&tipo_accion=1";
            Ajax_CargadoGeneralPagina('Solicitud/Rechazar/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }

        function Ajax_CargarAccion_EditarSolicitud(codigo, estado) {
            var strParametros = "&codigo=" + codigo + "&estado=" + estado + "&tipo_accion=1";
            Ajax_CargadoGeneralPagina('Solicitud/Afiliacion/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }
    
        function Ajax_CargarAccion_AprobarSolicitud() {
        
            var arrSolicitud = [];

            $('#FormularioBandejaEmpresa').find("input[type=checkbox]:checked").each(function () {

                arrSolicitud.push($(this).val());
            });

            var solicitud_list = JSON.stringify(arrSolicitud);
            var strParametros = "&solicitud_list=" + solicitud_list;
            Ajax_CargadoGeneralPagina('Solicitud/Aprobar/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }
    
    
        function SelecionarChecks(source) {
            checkboxes = document.getElementsByName('solicitud');
            for(var i=0, n=checkboxes.length;i<n;i++) 
            {
                checkboxes[i].checked = source.checked;
            }
        }
    
    <?php
    }                                                       
    ?>
    
</script>

<?php $cantidad_columnas = 7;?>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <form id="FormularioBandejaEmpresa" method="post">
        
        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('AfiliacionTitulo') . ' - ' . $estado_texto . 's'; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('AfiliacionSubtitulo'); ?></div>
        
            <div id="divErrorBusqueda" class="mensajeBD"> </div>
            
        <div style="clear: both"></div>
        
        <div align="left" class="BotonesVariasOpciones">
            
            <span class="BotonMinimalista" onclick="Ajax_CargarAccion_BandejaSolicitud(0)">
                <?php echo $this->lang->line('solicitud_estado_pendiente'); ?> 
            </span>
        </div>
        
        <div align="left" class="BotonesVariasOpciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarAccion_BandejaSolicitud(1)">
                <?php echo $this->lang->line('solicitud_estado_aprobado'); ?>
            </span>
        </div>
        
        <div align="left" class="BotonesVariasOpciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarAccion_BandejaSolicitud(2)">
                <?php echo $this->lang->line('solicitud_estado_cancelado'); ?>
            </span>
        </div>
        
        <?php
        
        if(count($arrRespuesta[0]) > 0 && $estado == 0)
        {
        
        ?>
        
            <div style="clear: both"></div>

            <br />

                <div align="left">

                    <div style="text-align: center !important; padding: 6px; border: 1px solid #cecece; border-radius: 10px; width: 260px; margin-left: 5%; background-color: #F3F3F3 !important;">

                        <span class="EnlaceSimple" onclick="Ajax_CargarAccion_AprobarSolicitud();" style="padding-left: 20px;">
                            <strong> <i class="fa fa-map-marker" aria-hidden="true"></i> Asignar Lote a Ejecutivo </strong>
                        </span>

                        <br /><br />

                        <input id="opcion_seleccion" type="checkbox" onClick="SelecionarChecks(this)" />
                        <label for="opcion_seleccion" style="font-size: 11px !important;"><span></span> Marcar/Desmarcar Todo</label>

                    </div>

            </div>
        
        <?php
        
        }
        
        ?>
        
        <div style="clear: both"></div>
        
        
        
        <div style="clear: both"></div>
        
		<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
                    <thead>

                        <tr class="FilaCabecera">

                            <!-- Similar al EXCEL -->

                            <th style="width:5%;"> </th>
                            
                            <th style="width:15%;">
                               <?php echo $this->lang->line('solicitud_nombre_empresa'); ?> </th>
                            <th style="width:10%;">
                               <?php echo $this->lang->line('empresa_departamento_detalle'); ?> </th>
                            <th style="width:10%;">
                               <?php echo $this->lang->line('empresa_municipio_detalle'); ?> </th>
                            <th style="width:10%;">
                               <?php echo $this->lang->line('empresa_zona_detalle'); ?> </th>
                            <th style="width:15%;">
                               <?php echo $this->lang->line('empresa_direccion_literal'); ?> </th>

                            <!-- Similar al EXCEL -->

                            <th style="width:25%;"> <?php echo $this->lang->line('TablaOpciones'); ?> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $mostrar = true;
                        if (count($arrRespuesta[0]) == 0)
                        {
                                $mostrar = false;
                        }

                        if ($mostrar)
                        {
                                $i=0;
                                $strClase = "FilaBlanca";
                                foreach ($arrRespuesta as $key => $value)
                                {
                                    $i++;

                                    ?> 
                                    <tr class="<?php echo $strClase; ?>">

                                        <!-- Similar al EXCEL -->

                                        <td style="text-align: center;">
                                            <?php
                                            if($estado == 0)
                                            {
                                            
                                                echo '<input name="solicitud" id="solicitud' . $value["solicitud_id"] . '" type="checkbox" name="solicitud_list[]" value="' . $value["solicitud_id"] . '">';
                                                echo '<label for="solicitud' . $value["solicitud_id"] . '"><span></span></label>';
                                            }
                                            ?>
                                        </td>
                                        
                                        <td style="text-align: center;">
                                            <?php echo $value["solicitud_nombre_empresa"]; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $value["solicitud_departamento_detalle"]; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $value["solicitud_ciudad_detalle"]; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $value["solicitud_zona_detalle"]; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $value["solicitud_direccion_literal"]; ?>
                                        </td>
                                        

                                        <!-- Similar al EXCEL -->

                                        <td style="text-align: center;">

                                            <span class="BotonSimple" onclick="Ajax_CargarAccion_DetalleSolicitud('<?php echo $value["solicitud_id"]; ?>', <?php echo $estado; ?>)">
                                                    <?php echo $this->lang->line('TablaOpciones_Detalle'); ?>
                                            </span>

                                            <?php

                                            if($estado == 0)
                                            {                                                        
                                            ?>

                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_RechazarSolicitud('<?php echo $value["solicitud_id"]; ?>', <?php echo $estado; ?>)">
                                                        <?php echo $this->lang->line('TablaOpciones_Rechazar'); ?>
                                                </span>

                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_EditarSolicitud('<?php echo $value["solicitud_id"]; ?>', <?php echo $estado; ?>)">
                                                        <?php echo $this->lang->line('TablaOpciones_Editar'); ?>
                                                </span>

                                            <?php
                                            }                                                    
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
                                <?php 
                                
                                    if($estado == 0)
                                    {
                                        echo $this->lang->line('TablaNoPendientes');
                                    }
                                    else
                                    {
                                        echo $this->lang->line('TablaNoRegistros'); 
                                    }
                                ?>
                                <br><br>
                            </td>
                        </tr>
                    <?php } ?>
		</table>
		
		

    </form>
        
    </div>
</div>