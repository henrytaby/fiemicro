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

    function RemitirObsDoc(prospecto_codigo, codigo_tipo_persona) {
        
        var cnfrm = confirm('<?php echo $this->lang->line('documento_remitir_consulta'); ?>');
        if(cnfrm != true)
        {
            return false;
        }
        else
        {
            Elementos_General_MostrarElementoFlotante(false);
            var strParametros = '&prospecto_codigo=' + prospecto_codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
            Ajax_CargadoGeneralPagina('Prospecto/ObservaDoc/Remitir', 'divVistaMenuPantalla', 'divErrorBusqueda', '', strParametros);
        }
    }

</script>
    
    <div id="FormularioVentanaAuxiliar" style="overflow-y: auto;">

        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('DocumentoProspectoTitulo') . '<br />' . $general_solicitante; ?></div>
        
        <div style="clear: both"></div>

        <br />

        <form id="FormularioRegistroLista" method="post">

            <input type="hidden" name="codigo_prospecto" id="codigo_prospecto" value="<?php echo $estructura_id; ?>" />
            
            <?php

            // Flag utilizado para saber si existe alguna observación, caso contrarío se mantendrá con "0".
            $sw_observacion = 0;
            
            if(count($arrRespuesta[0]) > 0)
            {

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
                            
                            <td style="width: 15%;">
                                
                                <?php                                
                                if($prospecto_rechazado == 0)
                                {
                                    if($value["documento_observado"])
                                    {
                                        echo '<span style="color: #ae0404; font-weight: bold;"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Fue Observado</span>';
                                        
                                        $sw_observacion = 1;
                                        
                                    }
                                    else
                                    {
                                ?>
                                        <span class="EnlaceSimple" onclick="Ajax_CargarAccion_ObservarDoc('<?php echo $value["documento_id"]; ?>', '<?php echo $estructura_id; ?>', '<?php echo $codigo_tipo_persona; ?>');">
                                            <strong> <i class="fa fa-reply" aria-hidden="true"></i> <?php echo $this->lang->line('documento_observar'); ?></strong>
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
            
            <br />
            
            <?php            
            if($sw_observacion == 1)
            {
            ?>
            
                <div style="text-align: center;">
                    <a onclick="RemitirObsDoc('<?php echo $estructura_id; ?>', '<?php echo $codigo_tipo_persona; ?>')" class="BotonMinimalista"> <?php echo $this->lang->line('documento_remitir_observación'); ?> </a>
                </div>
            
            <?php            
            }
            ?>
            
        </form>

        <br /><br />
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
    </div>