<script type="text/javascript">

    function Visor_DocumentoProspecto(documento_base64, documento) {
		
        var r = Math.random().toString(36).substr(2,16);
        var type = 'pto';
        window.open('Documento/Visualizar?' + r + '&type=' + type + '&documento_base64=' + documento_base64 + '&documento=' + documento, '_blank');

    }
    
    function Ajax_CargarAccion_HistorialDoc(codigo_documento, codigo_prospecto) {
        var strParametros = '&codigo_documento=' + codigo_documento + '&codigo_prospecto=' + codigo_prospecto;
        Ajax_CargadoGeneralPagina('Prospecto/Documento/Historico', 'divElementoFlotante', 'divErrorBusqueda', '', strParametros);
    }
    
    function Ajax_CargarAccion_AdministrarDoc(codigo_documento, codigo_prospecto) {
        Elementos_General_MostrarElementoFlotante(false);
        var strParametros = '&codigo_documento=' + codigo_documento + '&codigo_prospecto=' + codigo_prospecto;
        Ajax_CargadoGeneralPagina('Afiliador/Documento/Form', 'divVistaMenuPantalla', 'divErrorBusqueda', '', strParametros);
    }
    
</script>
    
    <div id="FormularioVentanaAuxiliar" style="overflow-y: auto;">

        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('DocumentoProspectoTitulo') . '<br />' . $arrProspecto[0]['empresa_nombre']; ?></div>
        
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

                                if(!$value["documento_digitalizado"])
                                {
                                    echo $this->lang->line('documento_no_digitalizado');                                    
                                }
                                else
                                {
                                ?>
                                    <span style="float: left !important; margin-right: 10px;" class="EnlaceSimple" onclick="Visor_DocumentoProspecto('<?php echo $estructura_id; ?>', '<?php echo $value["documento_id"]; ?>')">
                                        <strong><i class="fa fa-eye" aria-hidden="true"></i> <?php echo $this->lang->line('documento_si_digitalizado'); ?></strong>
                                    </span>
                                
                                    <span style="float: left !important;" class="EnlaceSimple" onclick="Ajax_CargarAccion_HistorialDoc('<?php echo $value["documento_id"]; ?>', '<?php echo $estructura_id; ?>')">
                                        <strong><i class="fa fa-history" aria-hidden="true"></i> <?php echo $this->lang->line('documento_si_digitalizado_historial'); ?></strong>
                                    </span>
                                <?php
                                }
                                ?>
                                
                            </td>
                            
                            <td style="width: 15%;">
                                
                                <span class="EnlaceSimple" onclick="Ajax_CargarAccion_AdministrarDoc('<?php echo $value["documento_id"]; ?>', '<?php echo $estructura_id; ?>');">
                                    <strong> <i class="fa fa-upload"></i> Subir Documento</strong>
                                </span>
                                
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
                    <a onclick="RemitirObsDoc('<?php echo $estructura_id; ?>')" class="BotonMinimalista"> <?php echo $this->lang->line('documento_remitir_observación'); ?> </a>
                </div>
            
            <?php            
            }
            ?>
            
        </form>

        <br /><br />
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
    </div>