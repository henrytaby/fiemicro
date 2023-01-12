    
<script type="text/javascript">

    function GenerarInformeConsolidado(codigo, version, tipo, repote, correlativo) {
        var dataObj = {"vista":"tabla"};
        dataObj.codigo = codigo;
        dataObj.version = version;
        dataObj.tipo = tipo;
        dataObj.repote = repote;
        dataObj.correlativo = correlativo;
        var data = encodeURI(JSON.stringify(dataObj));
        var w = window.open('about:blank');
        w.document.write('<form method="post" id="formID" action="Consultas/Informe">Generando Reporte...<input type="hidden" value="' + data + '" name="parametros"/></form>');
        w.document.getElementById("formID").submit();
        return false;
    }

</script>

    <div style="overflow-y: auto; height: 400px;">
        
        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('DetalleHistorialInforme') . '<br />' . $general_solicitante ; ?></div>

        <div style="clear: both"></div>

        <br />

        <form id="FormularioRegistroLista" method="post">
            
            <?php
            // Sólo muestra la opción si tiene el Perfil
            if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"], 15))
            {
            ?>
            
                <table class="tblListas Centrado " cellspacing="0" border="1" style="width: 100%;">

                    <tr>

                        <th style="width: 70%; font-weight: bold; text-align: center;">
                            <i class="fa fa-object-group" aria-hidden="true"></i> Visualizar Informe Consolidado Completo Actual
                        </th>

                        <th style="width: 15%; font-weight: bold; text-align: center;">
                            <span class="EnlaceSimple" onclick="GenerarInformeConsolidado('<?php echo $estructura_id; ?>', '0', 'actual', 'pdf', '0')">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Descargar PDF
                            </span>
                        </th>

                        <th style="width: 15%; font-weight: bold; text-align: center;">
                            <span class="EnlaceSimple" onclick="GenerarInformeConsolidado('<?php echo $estructura_id; ?>', '0', 'actual', 'excel', '0')">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Descargar EXCEL
                            </span>
                        </th>

                    </tr>

                </table>
            
            <?php
            }
            ?>
            
            <br /><br />
            
            <?php

            if (isset($arrRespuesta[0])) 
            {

            ?>

                <table class="tblListas Centrado " cellspacing="0" border="1" style="width: 100%;">
                    <thead>

                        <tr class="FilaCabecera">

                            <th style="width: 8%; font-weight: bold; text-align: center;">
                                N° Versión
                            </th>

                            <th style="width: 30%; font-weight: bold; text-align: center;">
                                Obtenida en Fecha
                            </th>
                            
                            <th style="width: 30%; font-weight: bold; text-align: center;">
                                Generado Por
                            </th>
                            
                            <th style="width: 2%; font-weight: bold; text-align: center;">
                                <i class="fa fa-shield" aria-hidden="true" title="Verificación de Integridad Registrada"></i>
                            </th>
                            
                            <th colspan="2" style="width: 30%; font-weight: bold; text-align: center;">
                                Opciones
                            </th>

                            <!-- Similar al EXCEL -->

                        </tr>
                    </thead>
                    <tbody>
                    <?php

                    $mostrar = true;

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
                                    <?php echo $this->mfunciones_generales->getFechaLiteral_tiempo($value["accion_fecha"]); ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    <?php echo $value["usuario_nombre"]; ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    <?php
                                    
                                    if(hash('sha256', $value["version_contenido"]) == $value["version_hash"])
                                    {
                                        echo '<i class="fa fa-check-square-o" aria-hidden="true" title="Integridad Registrada Verificada"></i>';
                                    }
                                    else
                                    {
                                        echo '<i class="fa fa-frown-o" aria-hidden="true" title="La Integridad de la versión no coincide con la registrada"></i>';
                                    }
                                    ?>
                                </td>

                                <td style="text-align: center;">
                                    <span class="EnlaceSimple" onclick="GenerarInformeConsolidado('<?php echo $estructura_id; ?>', '<?php echo $value["version_id"]; ?>', 'version', 'pdf', <?php echo $i; ?>)">
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Descargar PDF
                                    </span>
                                </td>

                                <td style="text-align: center;">
                                    <span class="EnlaceSimple" onclick="GenerarInformeConsolidado('<?php echo $estructura_id; ?>', '<?php echo $value["version_id"]; ?>', 'version', 'excel', <?php echo $i; ?>)">
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Descargar EXCEL
                                    </span>
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
                            }?>
                </table>

            <?php

            }

            else
            {            
                echo "<br /><div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> Aún No se Registró Versiones </div>";
            }

            ?>
            
        </form>

        <br /><br />
    </div>