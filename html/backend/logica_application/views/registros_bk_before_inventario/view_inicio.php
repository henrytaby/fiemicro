<script type="text/javascript">
<?php
    $estructura_id = $arrRespuesta[0]['prospecto_id'];
    $vista_actual = $arrRespuesta[0]['prospecto_ultimo_paso'];
    $codigo_rubro = $arrRespuesta[0]['camp_id'];
    $codigo_evaluacion = $arrRespuesta[0]['prospecto_evaluacion'];
?>

    function EnviarAuxiliar(estructura_id, codigo_rubro, home_ant_sig, tipo_registro="0")
    {
        var vista_actual = "datos_generales";
        var sin_guardar = 1;

        var strParametros = "&estructura_id=" + estructura_id + "&codigo_rubro=" + codigo_rubro + "&vista_actual=" + vista_actual + "&home_ant_sig=" + home_ant_sig + "&sin_guardar=" + sin_guardar + "&tipo_registro=" + tipo_registro;
        Ajax_CargadoGeneralPagina("../Pasos/Guardar", "divContenidoGeneral", "divErrorBusqueda", "SIN_FOCUS", strParametros);
    }
    
    function PreguntaAccion(mensaje, accion, valor="-1")
    {
        $("#pregunta_titulo").html(mensaje);
        $("#pregunta_opcion").modal();
        
        $("#pregunta_opcion").val(accion);
        $("#accion_valor").val(valor);
    }
    
    function PreguntaEvaluacion(mensaje, accion, valor="-1")
    {
        $("#evaluacion_titulo").html(mensaje);
        $("#evaluacion_opcion").modal();
        
        $("#pregunta_opcion").val(accion);
        $("#accion_valor").val(valor);
    }
    
    function VerCapacidad()
    {
        $("#capacidad_pago").modal();
    }
    
    function RealizaAccion(criterio=0)
    {
        switch ($("#pregunta_opcion").val()) {
            case 'unidad_familiar':

                EnviarAuxiliar("<?php echo $estructura_id; ?>", "<?php echo $codigo_rubro; ?>", "datos_generales", "unidad_familiar");

                break;
                
            case 'baja_familiar':

                EnviarAuxiliar($("#accion_valor").val(), "<?php echo $codigo_rubro; ?>", "datos_generales", "baja_familiar");

                break;
                
            case 'actividad_principal':

                EnviarAuxiliar($("#accion_valor").val(), "<?php echo $codigo_rubro; ?>", "datos_generales", "actividad_principal");

                break;
                
            case 'evaluacion':

                EnviarAuxiliar("<?php echo $estructura_id; ?>", criterio, "datos_generales", "evaluacion");

                break;
                
            case 'version_lead':

                EnviarAuxiliar("<?php echo $estructura_id; ?>", criterio, "datos_generales", "version_lead");

                break;
                
            default:

                break;
        }
    }
    
</script>

    <?php

        $clase_contenido_extra = 'contenido_formulario-nopasos';
        $clase_navbar_extra = 'navbar-nopasos';
    ?>

<nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_generales->getContenidoNavApp($estructura_id, "0"); ?> </nav>

<div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">

    <input type="hidden" name="accion_valor" id="accion_valor" />

    <div id="divErrorBusqueda" class="mensajeBD"> </div>
    
    <div class='col-sm-6'>

        <div style="text-align: right; clear: both">
            <label class='label-campo texto-centrado panel-heading color-azul' style="padding-bottom: 0px; margin-bottom: 0px; text-align: right;" for=''> Consolidado de Rubros <i class="fa fa-cubes" aria-hidden="true"></i> </label>
        </div>

        <div style="text-align: right; clear: both">
            <label class='label-campo color-azul' for='' style="text-align: right;"> Listado de Unidades Familaires registradas, el rubro y el Ingreso Mensual Promedio Ponderado. <br />Por favor, seleccione para desplegar las opciones </label>
        </div>

        <br />

        <div class="panel-group" id="accordion">

        <?php
        foreach ($arrRespuesta as $key => $value) 
        {
        ?>
            <div class="panel panel-default" style="margin-bottom: 2px;">
                <div class="panel-heading" style="cursor: pointer; height: 35px; padding: 5px 15px; font-size: 14px;" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $value['prospecto_id']; ?>">

                    <table style="width: 100%;">
                        <tr>
                            <td valign="middle" style="width: 70%; text-align: left; height: 35px; overflow: hidden;">
                                <?php echo $value['general_solicitante']; ?>
                            </td>
                            <td valign="middle" class="color-azul" style="width: 30%; text-align: right;">
                                <?php

                                    $texto_categoria = '';

                                    $nombre_rubro = $this->mfunciones_generales->GetValorCatalogo($value['camp_id'], 'nombre_rubro');

                                    // Icono de Tipo de Registro
                                    if($value['general_categoria'] == 1) { echo ' <i class="fa fa-user-circle-o" aria-hidden="true"></i> '; $texto_categoria= 'TITULAR'; }
                                    if($value['general_categoria'] == 2) { echo ' <i class="fa fa-users" aria-hidden="true"></i> '; $texto_categoria= 'FAMILIAR'; }

                                    // Icono del Rubro

                                    echo $this->mfunciones_generales->GetValorCatalogo($value['camp_id'], 'icono_rubro');

                                    if($value['prospecto_principal'] == 1) 
                                    {
                                        echo ' <i class="fa fa-star" aria-hidden="true"></i> ';
                                        
                                        $capacidad_texto = 'Capacidad de Pago de la Actividad Principal de acuerdo a la información registrada hasta el momento<br /><br /> <i>' . $value['general_solicitante'] . '</i>';
                                        
                                        $capacidad_ingreso = $value['capacidad_ingreso'];
                                        $capacidad_costo = $value['capacidad_costo'];
                                        $capacidad_utilidad_bruta = $value['capacidad_utilidad_bruta'];
                                        $capacidad_utilidad_operativa = $value['capacidad_utilidad_operativa'];
                                        $capacidad_resultado_neto = $value['capacidad_resultado_neto'];
                                        $capacidad_saldo_disponible = $value['capacidad_saldo_disponible'];
                                        $capacidad_margen_ahorro = $value['capacidad_margen_ahorro'];
                                    }
                                    
                                    echo "<br /><span style='font-size: 11px;'>Bs. " . number_format($value['ingreso_mensual_promedio'], 2, ',', '.') . "</span>";

                                ?>
                            </td>
                        </tr>
                    </table>

                </div>

                <div id="collapse<?php echo $value['prospecto_id']; ?>" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div style="text-align: center;">
                            <span class="nav-subtitulo"> REGISTRO DEL <?php echo "$texto_categoria | $nombre_rubro"; ?> </span>
                            <br />
                            <span class="nav-subtitulo"> <?php echo "C.I. " . $value['general_ci'] . " " . $this->mfunciones_generales->GetValorCatalogo($value['general_ci_extension'], 'extension_ci') . " | Teléfono: " . $value['general_telefono']; ?> </span>
                        </div>

                        <br />

                        <div class="row">
                            
                            <?php
                            if($value['general_categoria'] == 2)
                            {
                                echo '

                                <div class="col" style="text-align: left;">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" style="margin-left: 10px; border-radius: 0px !important; padding: 5px 0px !important; width: 70px !important; font-size: 13px;" onclick="PreguntaAccion(\'ATENCIÓN: Se dará de baja la Unidad Familiar <i>' . $value['general_solicitante'] . '</i> y ya no será parte del Cliente. <br /><br /> ¿Seguro que quiere continuar?\', \'baja_familiar\', \'' . $value['prospecto_id'] . '\')"><i class="fa fa-trash-o" aria-hidden="true"></i> Baja</button>
                                </div>';
                            }
                            ?>

                            <?php
                            if($value['prospecto_principal'] == 0)
                            {
                                echo '
                                <div class="col" style="text-align: center;">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal" style="margin-right: 5px; border-radius: 0px !important; padding: 5px 0px !important; width: 90%!important; font-size: 13px;" onclick="PreguntaAccion(\'Se marcará el registro de <i>' . $value['general_solicitante'] . '</i> como la Actividad Principal. <br /><br /> ¿Seguro que quiere continuar?\', \'actividad_principal\', \'' . $value['prospecto_id'] . '\')"><i class="fa fa-star" aria-hidden="true"></i> Principal</button>
                                </div>';
                            
                            }
                            ?>
                            
                            <div class="col" style="text-align: center;">
                                <button type="button" class="btn btn-primary" data-dismiss="modal" style="margin-right: 5px; border-radius: 0px !important; padding: 5px 0px !important; width: 90% !important; font-size: 13px;" onclick="EnviarAuxiliar('<?php echo $value['prospecto_id']; ?>', '<?php echo $value['camp_id']; ?>', '<?php echo str_replace("view_", "", $value['prospecto_ultimo_paso']); ?>')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Registro</button>
                            </div>
                            
                        </div>

                        <br />

                    </div>
                </div>
            </div>

        <?php
        }
        ?>

        </div>

    </div>

    <div class='col-sm-6'>

        <br /><br />

        <div style="text-align: right; clear: both">
            <label class='label-campo texto-centrado panel-heading color-azul' style="padding-bottom: 0px; margin-bottom: 0px; text-align: right;" for=''> Acciones del Cliente <i class="fa fa-cubes" aria-hidden="true"></i> </label>
        </div>

        <div style="text-align: right; clear: both">
            <label class='label-campo color-azul' for='' style="text-align: right;"> Por favor, seleccione las acciones a efectuarse al Cliente, recuerde que éstas acciones no se pueden deshacer. </label>
        </div>
        
        <br />
        
        <div class="row">
            <div class="col" style="text-align: center;">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 60% !important; font-size: 13px; height: 35px;" onclick="VerCapacidad()"><i class="fa fa-wpexplorer" aria-hidden="true"></i> Ver Capacidad de Pago</button>
            </div>
        </div>
        
        <br />
        
        <div class="row">
            <div class="col" style="text-align: right;">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 90% !important; font-size: 12px; height: 35px;" onclick="PreguntaAccion('Al registrar una nueva Actividad Económica, deberá asociarlo a un Rubro específico y no podrá eliminarla. <br /><br /> ¿Seguro que quiere continuar?', 'unidad_familiar')"><i class="fa fa-users" aria-hidden="true"></i> Nueva Actividad Eco.</button>
            </div>
            <div class="col" style="text-align: right;">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 90% !important; font-size: 13px; height: 35px;" onclick="PreguntaAccion('Se procederá a Crear una Versión del Cliente con la información registrada hasta el momento. La generación de versiones es de completa responsabilidad del Agente.<br />ESTA ACCIÓN NO SE PUEDE DESHACER. <br /><br /> ¿Seguro que quiere continuar?', 'version_lead')"><i class="fa fa-object-group" aria-hidden="true"></i> Crear Versión </button>
            </div>
        </div>
        
        <br /><br /><br />
        
        <ul class="nav nav-tabs">
            <li class="active"><a id="tab1" class="panel-heading" data-toggle="tab" href="#tab_evaluacion"> <i class="fa fa-gavel" aria-hidden="true"></i> Evaluación Cliente </a></li>
            <li><a id="tab2" class="panel-heading" data-toggle="tab" href="#tab_versiones"> <i class="fa fa-history" aria-hidden="true"></i> Versiones </a></li>
        </ul>
        
        <div class="tab-content">
        
            <div id="tab_evaluacion" class="tab-pane fade in active tab_plomo">

                <br />

                <div class="row">
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado' for=''> <?php echo ($codigo_evaluacion!=0 ? "<i class='fa fa-dot-circle-o' aria-hidden='true'></i> " : ""); ?> <?php echo $this->mfunciones_generales->GetValorCatalogo($codigo_evaluacion, 'prospecto_evaluacion'); ?> </label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 90% !important; font-size: 13px; height: 35px;" onclick="PreguntaEvaluacion('Se procederá a Registrar o Actualizar la Evaluación del Cliente, considere todos los elementos e información del mismo para establecer su criterio.<br /><br />Toda acción se registrará en el seguimiento del Cliente.', 'evaluacion')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Evaluación </button>
                    </div>
                </div>

                <br />

            </div>

            <div id="tab_versiones" class="tab-pane fade tab_plomo" style="padding: 20px;">

                <?php

                $mostrar = false;

                if (isset($arrVersiones[0])) 
                {
                    $mostrar = true;
                ?>

                    <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

                        <tr class="FilaGris">

                            <td style="width: 30%; font-weight: bold; text-align: center; font-size: 14px;">
                                N° Versión
                            </td>

                            <td style="width: 70%; font-weight: bold; text-align: center; font-size: 14px;">
                                Obtenida en Fecha
                            </td>

                        </tr>

                        <?php

                        if ($mostrar) 
                        {
                            $i = 0;

                            foreach ($arrVersiones as $key => $value) 
                            {
                                $i++;
                        ?>
                                <tr class="FilaBlanca">

                                    <td style="text-align: center; font-size: 14px;">
                                        <?php echo $i; ?>
                                    </td>

                                    <td style="text-align: center; font-size: 14px;">
                                        <?php echo $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M_S($value["accion_fecha"]); ?>
                                    </td>

                                </tr>

                        <?php
                            }
                        }
                        ?>

                    </table>

                <?php

                }

                else
                {            
                    echo "<br /><div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> Aún No se Registró Versiones </div>";
                }

                ?>

            </div>
        </div>
    </div>

</div>

<br /><br />

<div class="modal fade" id="pregunta_opcion" role="dialog">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="pregunta_titulo">Seleccione para Continuar</h4>
        </div>
        <div class="modal-body">
            <div style="text-align: center;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 130px !important;">Cancelar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 150px !important;" onclick="RealizaAccion()">Si, Continuar</button>
            </div>
        </div>
  </div>
</div>
</div>

<div class="modal fade" id="evaluacion_opcion" role="dialog">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="evaluacion_titulo">Seleccione para Continuar</h4>
        </div>
        <div class="modal-body">
            <div style="text-align: center;">
                <button type="button" class="btn btn-danger" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 40% !important;" onclick="RealizaAccion(2)"> Rechazar </button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 40% !important;" onclick="RealizaAccion(1)"> Aprobar </button>
            </div>
            <br />
            <div style="text-align: center;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 80% !important;">Cancelar</button>
            </div>
        </div>
  </div>
</div>
</div>

<div class="modal fade" id="capacidad_pago" role="dialog">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="evaluacion_titulo"><?php echo $capacidad_texto; ?></h4>
        </div>
        <div class="modal-body">
            <div style="text-align: center;">
                
                <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">
                    
                    <tr>
                        <td style="width: 65%; text-align: left;">
                            Ingreso / Ventas
                        </td>
                        <td style="width: 35%; text-align: right;">
                            <?php echo number_format($capacidad_ingreso, 2, ',', '.'); ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="width: 65%; text-align: left;">
                            Costo Ventas
                        </td>
                        <td style="width: 35%; text-align: right;">
                            <?php echo number_format($capacidad_costo, 2, ',', '.'); ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="width: 65%; text-align: left;">
                            Utilidad Bruta
                        </td>
                        <td style="width: 35%; text-align: right;">
                            <?php echo number_format($capacidad_utilidad_bruta, 2, ',', '.'); ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="width: 65%; text-align: left; font-weight: bold;">
                            Utilidad Operativa
                        </td>
                        <td style="width: 35%; text-align: right; font-weight: bold;">
                            <?php echo number_format($capacidad_utilidad_operativa, 2, ',', '.'); ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="width: 65%; text-align: left; font-weight: bold;">
                            Utilidad Neta
                        </td>
                        <td style="width: 35%; text-align: right; font-weight: bold;">
                            <?php echo number_format($capacidad_resultado_neto, 2, ',', '.'); ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="width: 65%; text-align: left;">
                            Saldo Disponible
                        </td>
                        <td style="width: 35%; text-align: right;">
                            <?php echo number_format($capacidad_saldo_disponible, 2, ',', '.'); ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="width: 65%; text-align: left; font-weight: bold;">
                            Margen de Ahorro
                        </td>
                        <td style="width: 35%; text-align: right; font-weight: bold;">
                            <?php echo number_format($capacidad_margen_ahorro, 2, ',', '.'); ?>
                        </td>
                    </tr>
                    
                </table>
            </div>
        </div>
  </div>
</div>
</div>