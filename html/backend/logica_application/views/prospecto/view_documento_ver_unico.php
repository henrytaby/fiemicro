<script type="text/javascript">

    function Visor_DocumentoProspecto(documento_base64, documento, type) {
		
        var r = Math.random().toString(36).substr(2,16);
        window.open('Documento/Visualizar?' + r + '&type=' + type + '&documento_base64=' + documento_base64 + '&documento=' + documento, '_blank');
		
    }
    
    function Ajax_CargarAccion_HistorialDoc(codigo_documento, codigo_prospecto, codigo_tipo_persona) {
        var strParametros = '&codigo_documento=' + codigo_documento + '&codigo_prospecto=' + codigo_prospecto + "&codigo_tipo_persona=" + codigo_tipo_persona;
        Ajax_CargadoGeneralPagina('Prospecto/Documento/Historico', 'divElementoFlotante', 'divErrorBusqueda', '', strParametros);
    }
    
    <?php
    
    if($prospecto_rechazado == 0)
    {
        echo "
            function Ajax_CargarAccion_ObservarDoc(codigo_documento, codigo_prospecto, codigo_tipo_persona) {
                Elementos_General_MostrarElementoFlotante(false);
                var strParametros = '&codigo_documento=' + codigo_documento + '&codigo_prospecto=' + codigo_prospecto + '&codigo_tipo_persona=' + codigo_tipo_persona;
                Ajax_CargadoGeneralPagina('Prospecto/ObservaDoc/Ver', 'divVistaMenuPantalla', 'divErrorBusqueda', '', strParametros);
            }
            ";
    }
    
    ?>

</script>
    
    <div id="FormularioVentanaAuxiliar" style="overflow-y: auto;">

        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('DocumentoProspectoTitulo') . '<br />' . $general_solicitante; ?></div>
        
        <div style="clear: both"></div>

        <br />

        <form id="FormularioRegistroLista" method="post">

            <input type="hidden" name="codigo_prospecto" id="codigo_prospecto" value="<?php echo $estructura_id; ?>" />
            
            <?php

            if(count($arrRespuesta[0]) > 0)
            {
            ?>
                <?php
                
                if((int)$codigo_tipo_persona == 13 && $this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS) && $this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_OBSERVAR_DOCUMENTOS) && $this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DEVOLVER_PROSPECTO))
                {
                    if($norm_consolidado == 1)
                    {
                ?>
                        <div style="text-align: right;">
                            <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DocumentoProspecto('<?php echo $estructura_id; ?>', '<?php echo $codigo_tipo_persona; ?>');" style="padding-right: 20px;">
                                <strong> <i class="fa fa-bookmark" aria-hidden="true"></i> ¿Observar? </strong>
                            </span>
                        </div>
                <?php
                    }
                }
                ?>
            
                <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                    <?php $strClase = "FilaGris"; ?>

                    <?php

                    foreach ($arrRespuesta as $key => $value) 
                    {
                    
                    ?>
                    
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 50%;">
                                <strong> <i class="fa fa-clone" aria-hidden="true"></i> <?php echo $value["documento_detalle"]; ?></strong>
                            </td>
                            
                            <td style="width: 35%;">
                                <?php 

                                if($value["documento_id"] == 0)
                                {
                                    echo "Registro en Formulario(s)";
                                }
                                else
                                {
                                    if(!$value["documento_digitalizado"])
                                    {
                                        echo $this->lang->line('documento_no_digitalizado');                                    
                                    }
                                    else
                                    {
                                        switch ((int)$codigo_tipo_persona) {
                                            case 6:
                                                // Solicitud de Crédito
                                                $tipo_doc = 'sol';

                                                break;

                                            case 13:
                                                // Normalizador/Cobrador
                                                $tipo_doc = 'norm';

                                                break;

                                            default:

                                                $tipo_doc = 'pto';

                                                break;
                                        }
                                        
                                    ?>
                                        <span style="float: left !important; margin-right: 10px;" class="EnlaceSimple" onclick="Visor_DocumentoProspecto('<?php echo $estructura_id; ?>', '<?php echo $value["documento_id"]; ?>', '<?php echo $tipo_doc; ?>')">
                                            <strong><i class="fa fa-eye" aria-hidden="true"></i> <?php echo $this->lang->line('documento_si_digitalizado'); ?></strong>
                                        </span>

                                        <span style="float: left !important;" class="EnlaceSimple" onclick="Ajax_CargarAccion_HistorialDoc('<?php echo $value["documento_id"]; ?>', '<?php echo $estructura_id; ?>', '<?php echo $codigo_tipo_persona; ?>')">
                                            <strong><i class="fa fa-history" aria-hidden="true"></i> <?php echo $this->lang->line('documento_si_digitalizado_historial'); ?></strong>
                                        </span>
                                <?php
                                    }
                                }
                                ?>
                                
                            </td>
                            
                        </tr>
                        
                    <?php

                    }

                    ?>

                </table>

            <?php

            }

            else
            {
                echo $this->lang->line('TablaNoResultados');
            }

            ?>
            
        </form>

        <br /><br />
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
    </div>