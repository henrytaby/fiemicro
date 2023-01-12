<script type="text/javascript">
<?php
    $estructura_id = $arrRespuesta[0]['norm_id'];
    $vista_actual = $arrRespuesta[0]['norm_ultimo_paso'];
    $codigo_rubro = $arrRespuesta[0]['camp_id'];
    $norm_finalizacion = $arrRespuesta[0]['norm_finalizacion'];
?>

    $(document).ready(function(){ 
        $('.evaluacion').find("input[type=text]").each(function(ev)
        {
            $(this).attr("placeholder", " :: Registrar Justificación ::");
        });
        
        $('#divContenidoGeneral').css('padding-bottom','100px');
        
        $('#registro_num_proceso').on('click', function () {
            if($(this).val() == 0)
            {
                $(this).select();
            }
        });
        
        norm_finalizacion();
    });
    
    function norm_finalizacion()
    {
        if(parseInt($("#norm_finalizacion").val()) == -1)
        {
            $('#button_norm_finalizacion').prop('disabled', true);
        }
        else
        {
            $('#button_norm_finalizacion').prop('disabled', false);
        }
    }

    $("#norm_finalizacion").on('change', function(){
        norm_finalizacion();
    });
    
    function EnviarAuxiliar(estructura_id, codigo_rubro, home_ant_sig, tipo_registro="0")
    {
        var vista_actual = "datos_generales";
        var sin_guardar = 1;

        var strParametros = "&estructura_id=" + estructura_id + "&codigo_rubro=" + codigo_rubro + "&vista_actual=" + vista_actual + "&home_ant_sig=" + home_ant_sig + "&sin_guardar=" + sin_guardar + "&tipo_registro=" + tipo_registro;
        Ajax_CargadoGeneralPagina("../Pasos/Guardar", "divContenidoGeneral", "divErrorBusqueda", "SIN_FOCUS", strParametros);
    }
    
    function PreguntaAccion(mensaje, accion, valor="-1")
    {
        $("#pregunta_opcion").modal();
        $("#pregunta_opcion").val(accion);
        $("#accion_valor").val(valor);
    }
    
    function PreguntaVisita()
    {
        $("#pregunta_visita").modal();
    }
    
    function RealizaAccion(criterio=0)
    {
        switch ($("#pregunta_opcion").val()) {
            
            case 'norm_finalizacion':

                EnviarAuxiliar("<?php echo $estructura_id; ?>", "<?php echo $codigo_rubro; ?>", $("#norm_finalizacion").val(), "norm_finalizacion");

                break;
                
            default:

                break;
        }
    }
    
    $("#NormPanelPrincipal").show();
    $("#NormPanelVisitas").hide();

    function MostrarPanelVisitas()
    {
        $("#NormPanelPrincipal").hide();
        $("#NormPanelVisitas").fadeIn(500);
    }
    
    function OcultarPanelVisitas()
    {
        $("#NormPanelPrincipal").fadeIn(500);
        $("#NormPanelVisitas").hide();
    }
    
    function VisitaNorm(codigo_visita, codigo_contador)
    {
        var strParametros = "&norm_id=<?php echo $estructura_id; ?>" + "&codigo_visita=" + codigo_visita + "&codigo_contador=" + codigo_contador;
        Ajax_CargadoGeneralPagina("../NormVisita/Form", "NormPanelVisitas", "divErrorBusqueda", "SIN_FOCUS", strParametros);
    }
    
    function ShowHidePanelVisitas() {
        $("#panel_body_visitas").slideToggle();
    }
    
</script>

    <?php

        $clase_contenido_extra = 'contenido_formulario-nopasos';
        $clase_navbar_extra = 'navbar-nopasos';
    ?>

<nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_cobranzas->getContenidoNavApp($estructura_id, "0"); ?> </nav>

<div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">

    <input type="hidden" name="accion_valor" id="accion_valor" />
    <input type="hidden" name="pdf_link" id="pdf_link" />
    
    <input type="hidden" name="aux_nro_operacion" id="aux_nro_operacion" value="<?php echo $arrRespuesta[0]['registro_num_proceso']; ?>" />

    <div id="divErrorBusqueda" class="mensajeBD"> </div>
    
    <div id="NormPanelPrincipal">
    
        <div class='col-sm-6'>

            <?php
                if($arrRespuesta[0]['cv_fecha_compromiso_check'])
                {
                    echo '<div class="alert alert-danger" style="font-weight: bold; text-align:center;"> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> La última visita registrada tiene la ' . mb_strtolower($this->lang->line('cv_fecha_compromiso')) . ' vencida. </div>';
                }
            ?>
            
            <div style="text-align: right; clear: both">
                <label class='label-campo texto-centrado panel-heading color-azul' style="padding-bottom: 0px; margin-bottom: 0px; text-align: right;" for=''> Registro Onboarding<br /><?php echo $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app_singular'); ?> <i class="fa fa-cubes" aria-hidden="true"></i> </label>
            </div>

            <div style="text-align: right; clear: both">
                <label class='label-campo color-azul' for='' style="text-align: right;"> Registre la información del caso <?php echo $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app_singular'); ?>, registrando las visitas y el criterio de Finalización de la Gestión para poder Consolidar.</label>
            </div>

            <br />

            <div class="row">
                <div class="col" style="text-align: center;">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 255px !important; font-size: 13px; height: 35px;" onclick="EnviarAuxiliar('<?php echo $estructura_id; ?>', '<?php echo $codigo_rubro; ?>', '<?php echo str_replace("view_", "", $vista_actual); ?>', '<?php echo $arrRespuesta[0]['ejecutivo_id']; ?>')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Registrar Información </button>
                </div>
            </div>
            
            <br />
            
            <div class="row">
                <div class="col" style="text-align: center;">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 255px !important; font-size: 13px; height: 35px;" onclick="PreguntaAccion('', 'norm_finalizacion')"><i class="fa fa-check-square-o" aria-hidden="true"></i> Finalización de la Gestión </button>
                </div>
            </div>

        </div>

        <br />

        <div class='col-sm-6'>
            
            <div class="row">
                <div class="col" style="text-align: center;">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 255px !important; font-size: 13px; height: 35px;" onclick="PreguntaVisita();"><i class="fa fa-address-book-o" aria-hidden="true"></i> Registrar Nueva Visita </button>
                </div>
            </div>
            
            <br />
            
            <div class="panel panel-default informacion_general">
                <div class="panel-heading" style="font-size: 1.2em;">
                    <?php echo ($visitas_contador>0 ? '<span style="font-weight: normal; text-shadow: 0 0 black;" class="AyudaTooltip" data-balloon-length="medium" data-balloon="' . $this->lang->line('cv_panel_ayuda') . '" data-balloon-pos="top"></span>' : ''); ?>
                    Visitas Registradas: 
                    <?php echo $visitas_contador . ($visitas_contador>0 ? '<span onclick="ShowHidePanelVisitas()" style="float: right; cursor: pointer;"> <i class="fa fa-arrows-v" aria-hidden="true"></i> </span> <div style="clear: both;"></div>' : ''); ?>
                </div>
                <div id="panel_body_visitas" class="panel-body" style="padding: 0px; overflow-x: auto;">

                    <?php
                    if($visitas_contador > 0)
                    {
                    ?>
                        <table class="tblListas Centrado" style="width: 100% !important; padding: 0px; font-size: 0.9em;" border="0">

                            <tr class="FilaGris">

                                <th style="width: 5%; font-weight: bold; text-align: center; font-size: 1.2em;">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </th>
                                <th style="width: 90%; font-weight: bold; text-align: center;">
                                    Información de la Visita
                                </th>
                                <th style="width: 5%; font-weight: bold; text-align: center;">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </th>
                            </tr>
                    
                    <?php
                    
                        $i = 0;
                        foreach ($arrVisita as $key => $value) 
                        {
                            $i++;
                        ?>
                    
                            <tr class="FilaBlanca">
                                <td style="text-align: center; font-weight: bold;">
                                    <button onclick="VisitaNorm(<?php echo $value['cv_id'] . ', ' . $i; ?>);" type="button" class="btn btn-primary" data-dismiss="modal" style="font-size: 1em; border-radius: 0px !important; padding: 12px 4px !important; width: 100% !important;">
                                        #<?php echo $i; ?>
                                    </button>
                                </td>
                                
                                <td style="text-align: justify;">
                                    
                                    <?php
                                    
                                    echo "<div class='col-sm-3'><b>Fecha de Registro: </b>" . $value["cv_fecha"] . "</div>";
                                    echo "<div class='col-sm-3'><b>" . $this->lang->line('cv_resultado') . ": </b>" . $value["cv_resultado"] . "</div>";
                                    echo "<div class='col-sm-4'>
                                        <b>" . $this->lang->line('cv_fecha_compromiso') . ": </b>" . $value["cv_fecha_compromiso"] . 
                                        ($value["cv_observaciones"]=='' ? "" : "<br /><b>" . $this->lang->line('cv_observaciones') . ": </b>" . $value["cv_observaciones"]) . "</div>";
                                    
                                    ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    <?php
                                        echo '<span style="cursor: pointer;" data-balloon-length="small" data-balloon="' . ((int)$value["cv_checkin"]==0 ? 'Check Visita no registrado' : 'Check Visita: ' . $value["cv_checkin_fecha"]) . '" data-balloon-pos="left">';
                                        echo ((int)$value["cv_checkin"]==0 ? '<span style="color: #db1b1c;"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>' : '<i class="fa fa-check-square-o" aria-hidden="true"></i>'); 
                                        echo '</span>';
                                    ?>
                                </td>
                                
                            </tr>
                    
                        <?php
                        }
                        
                        echo '</table><div style="text-align: right; clear: both"></div>';
                    }
                    else
                    {
                        echo '<p style="padding: 10px 5px; text-align: center; font-weight: bold;">Aún no se registraron visitas.</p>';
                    }
                    ?>
                    
                </div>
            </div>
            
            <div class="row" style="border: 1px solid #bcbcbc; padding: 10px 10px;">
                <div class="col" style="text-align: justify; font-style: italic; font-weight: bold; font-size: 12px;">

                    <?php

                    // [-- Sección ALPHA

                    $icono_info = '<i class="fa fa-info-circle" aria-hidden="true"></i> ';

                    // Sección ALPHA --]

                    echo '<p style="margin-bottom: 5px;">' . $icono_info . ' Asociado a: ' . $arrRespuesta[0]['nombre_agencia'] . ((int)$arrRespuesta[0]["estado_region"]==1 ? '' : ' (Cerrada)') . '</p>';

                    echo '<p style="margin-bottom: 5px;">' . $icono_info . ' ' . $this->lang->line('norm_finalizacion') . ': ' . $this->mfunciones_cobranzas->GetValorCatalogo($norm_finalizacion, 'norm_finalizacion') . '</p>';

                    if((int)$norm_finalizacion>0)
                    {   
                        if(str_replace(' ', '', $arrRespuesta[0]['norm_ultimo_paso']) != 'view_final')
                        {
                            echo '<p style="margin-bottom: 5px; color: #ff0000;">' . $icono_info . 'Registro incompleto. Recomendado completar el registro de la información antes de consolidar.</p>';
                        }

                        echo $icono_info . 'Para que se aplique la información y criterios establecidos, debe consolidar el registro.';
                    }

                    ?>

                </div>
            </div>

        </div>

    </div>
    
    <div id="NormPanelVisitas"> </div>

    <br /><br />

    <div class="modal fade" id="pregunta_visita" role="dialog" style="top: -100px !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 0px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-size: 15px !important;">
                REGISTRAR NUEVA VISITA
                <i>
                    <?php
                    
                    if(!$this->mfunciones_microcreditos->CheckIsMobile())
                    {
                        echo '<br /><br /><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Está realizando la acción desde el BackEnd, por lo que no podrá registrar el CHECK VISITA con la ubicación GPS del dispositivo, es altamente recomendado que realice esta acción desde el App Móvil para hacer uso de dicha función. También puede "Forzar Check Visita" desde las opciones del registro.';
                    }
                    
                    ?>
                    
                    <br /><br />
                    Al registrar una nueva visita se marcará la fecha actual de la acción, debiendo registrar su información y "Check Visita" respectivo. Al proceder con el guardado, no podrá eliminar la visita registrada.
                    
                    <br /><br />
                    ¿Confirma que quiere continuar?
                </i>
                </h4>
            </div>
            
            <br /><br />
            
            <div class="" style="text-align: center;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 130px !important;">Cancelar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 135px !important;" onclick="VisitaNorm(-1, 0);">Si, continuar</button>
            </div>
            
            <br /><br />
            
      </div>
    </div>
    </div>
    
    <div class="modal fade" id="pregunta_opcion" role="dialog" style="top: -50px !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 0px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="aprobar_titulo" style="font-size: 15px !important;">

                    FINALIZACIÓN DE LA GESTIÓN

                </h4>
            </div>
            <div class="modal-body">

                <label class='label-campo' for='norm_finalizacion'> Por favor seleccione el criterio: </label>

                <?php

                    $arrOpcionesFin = array();
                    for($i=1; $i<=4; $i++)
                    {
                        $arrOpcionesFin[] = array("id" => $i, "campoDescrip" => $this->mfunciones_cobranzas->GetValorCatalogo($i, 'norm_finalizacion'));
                    }

                    echo html_select('norm_finalizacion', $arrOpcionesFin, 'id', 'campoDescrip', '', $arrRespuesta[0]['norm_finalizacion']);
                ?>

                <br /><br /><br />
                <div style="text-align: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 130px !important;">Cancelar</button>
                    <button id="button_norm_finalizacion" type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 135px !important;" onclick="RealizaAccion();">Guardar</button>
                </div>
            </div>
      </div>
    </div>
    </div>