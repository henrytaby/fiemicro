<script type="text/javascript">
<?php
// Si no existe informaci?n, no se mostrar? como tabla con columnas con ?rden
if(count($arrRespuesta[0]) > 0)
{
?>  
    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": -1,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[0, "asc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
<?php
}
?>
    function Ajax_CargarAccion_DetalleEmpresa(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Empresa/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_RegistroEmpresa(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Registro/Empresa/Form', 'divVistaMenuPantalla', "divErrorBusqueda", 'SIN_FOCUS', strParametros);
    }
    
    function Ajax_CargarAccion_MapaEmpresa(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Empresa/Zona/Ver', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_GeoEmpresa() {
        
        var arrGeo = [];
        
        $('#FormularioBandejaEmpresa').find("input[type=checkbox]:checked").each(function () {
            
            arrGeo.push($(this).val());
        });
        
        var geo_list = JSON.stringify(arrGeo);
        var strParametros = "&geo_list=" + geo_list;
        Ajax_CargadoGeneralPagina('Empresa/Geo/Ver', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function SelecionarChecks(source) {
        checkboxes = document.getElementsByName('geo');
        for(var i=0, n=checkboxes.length;i<n;i++) 
        {
            checkboxes[i].checked = source.checked;
        }
    }
    
</script>

<?php $cantidad_columnas = 6;?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> Gesti??n de Informaci??n de Empresas Registradas </div>
            <div class="FormularioSubtituloComentarioNormal "> 
                En este apartado podr?? actualizar la informaci??n de las empresas que se encuentren registradas en el sistema para dar mantenimiento. Esta actualizaci??n s??lo tendr?? efecto en la Base de Datos del Sistema.
                <br /><br />
                En el formulario de edici??n, podr?? Ver y/o Registrar la geolocalizaci??n de la empresa o hacerlo directamente en la opci??n "Ver/Registrar Geolocalizaci??n".
                <br /><br />
                Puede Geoposicionar las empresas para visualizarlas en mapa. Seleccione la(las) empresas que requiere visualizar. 
            </div>
        
        <div style="clear: both"></div>
        
        <div align="left" class="BotonesVariasOpciones">

            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Nuevo/Empresa/Form')">
                <?php echo $this->lang->line('TablaOpciones_NuevoCatalogo'); ?>
            </span>

        </div>
        
        <div style="clear: both"></div>
        
            <div style="text-align: right !important; margin-left: 8%;">

                <?php
            
                    $direccion_bandeja = 'Menu/Principal';

                    if(isset($_SESSION['direccion_bandeja_actual']))
                    {
                        $direccion_bandeja = $_SESSION['direccion_bandeja_actual'];
                    }

                ?>
                
                <span class="EnlaceSimple" onclick="Ajax_CargarOpcionMenu('<?php echo $direccion_bandeja; ?>');" style="padding-right: 20px;">
                    <strong> <i class="fa fa-refresh" aria-hidden="true"></i> Actualizar Bandeja </strong>
                </span>
                
            </div>
        
            <br />
        
            <div align="left">
            
                <div style="text-align: center !important; padding: 6px; border: 1px solid #cecece; border-radius: 10px; width: 260px; margin-left: 5%; background-color: #F3F3F3 !important;">

                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_GeoEmpresa();" style="padding-left: 20px;">
                        <strong> <i class="fa fa-map-marker" aria-hidden="true"></i> Geoposicionar Empresas Seleccioandas </strong>
                    </span>

                    <br /><br />

                    <input id="opcion_seleccion" type="checkbox" onClick="SelecionarChecks(this)" />
                    <label for="opcion_seleccion" style="font-size: 11px !important;"><span></span> Marcar/Desmarcar Todo</label>

                </div>
                
            </div>
        
        <div id="divErrorBusqueda" class="mensajeBD">  </div>
        
            <form id="FormularioBandejaEmpresa" method="post">
                
                <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
                    <thead>

                        <tr class="FilaCabecera">

                            <th style="width:5%;">
                               N??
                            </th>

                            <!-- Similar al EXCEL -->

                            <th style="width:25%;">
                               <?php echo $this->lang->line('empresa_nombre'); ?> </th>
                            <th style="width:10%;">
                               <?php echo $this->lang->line('empresa_categoria_detalle'); ?> </th>
                            <th style="width:15%;">
                               <?php echo $this->lang->line('empresa_nit'); ?> </th>
                            <th style="width:10%;">
                               <?php echo $this->lang->line('empresa_departamento_detalle'); ?> </th>
                            <th style="width:10%;">
                               ??Geolocalizaci??n? </th>

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

                                    <td style="text-align: center;">
                                            <?php echo $i; ?>
                                    </td>

                                    <!-- Similar al EXCEL -->

                                    <td style="text-align: center;">
                                        <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleEmpresa('<?php echo $value["empresa_id"]; ?>')">

                                            <?php
                                            if($value["empresa_categoria_codigo"] == 1)
                                            {
                                                echo IMAGEN_COMERCIO;
                                            }
                                            else
                                            {
                                                echo IMAGEN_ESTABLECIMIENTO;
                                            }
                                            ?>

                                            <?php echo $value["empresa_nombre"]; ?>
                                        </span>
                                    </td>

                                    <td style="text-align: center;">
                                        <?php echo $value["empresa_categoria_detalle"]; ?>
                                    </td>

                                    <td style="text-align: center;">
                                        <?php echo $value["empresa_nit"]; ?>
                                    </td>

                                    <td style="text-align: center;">
                                        <?php echo $value["empresa_departamento_detalle"]; ?>
                                    </td>

                                    <td style="text-align: center;">

                                        <?php

                                        if($value["empresa_direccion_geo"] == '')
                                        {
                                            echo "No Registrado";
                                        }
                                        else
                                        {
                                            echo '<input name="geo" id="geo' . $value["empresa_id"] . '" type="checkbox" name="geo_list[]" value="' . $value["empresa_id"] . '">';
                                            echo '<label for="geo' . $value["empresa_id"] . '"><span></span></label>';
                                            echo "Registrado ";
                                        }

                                        ?>

                                    </td>

                                    <!-- Similar al EXCEL -->

                                    <td style="text-align: center;">

                                        <span class="BotonSimple" onclick="Ajax_CargarAccion_RegistroEmpresa('<?php echo $value["empresa_id"]; ?>')">
                                            <?php echo $this->lang->line('TablaOpciones_Editar'); ?>
                                        </span>

                                        <span class="BotonSimple" onclick="Ajax_CargarAccion_MapaEmpresa('<?php echo $value['empresa_id']; ?>')">
                                            Ver/Registrar <br/> Geolocalizaci??n
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
                                <?php echo $this->lang->line('TablaNoPendientes'); ?>
                                <br><br>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                
            </form>
        
        <br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Registro/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
        
    </div>
</div>