<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios('sincombo');
    
    if($tipo_registro == 'nuevo_norm')
    {
        echo '$(".informacion_operacion").hide();';
    }
    
?>

    $(document).ready(function() {
        $("div.modal-backdrop").remove();
    });
    
    function FormularioDireccion(dir_contador, dir_codigo, registro_tipo, registro_codigo) {
        
        // Ocultar navegacion y mostrar panel de registro direccion
        $(".main_nav_bloque").hide();
        $(".diraux_nav_bloque").fadeIn();
        
        if(dir_contador == -1)
        {
            $("#diraux_nav_bloque_titulo").html("NUEVA");
        }
        else
        {
            $("#diraux_nav_bloque_titulo").html("#" + dir_contador);
        }
        
        $("#home_ant_sig").val("norm_direccion");
        
        $("#direccion_panel").hide();
        
        var strParametros = "&dir_contador=" + dir_contador + "&dir_codigo=" + dir_codigo + "&registro_tipo=" + registro_tipo + "&registro_codigo=" + registro_codigo;
        Ajax_CargadoGeneralPagina('../NormDir/Form', 'direccion_formulario', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
    
    function Cancelar_FormularioDireccion() {
        $("#home_ant_sig").val("norm_direccion");
        EnviarSinGuardar();
    }
    
</script>

    <?php
    
        $text_unidad_familiar = '';
        if($tipo_registro == 'nuevo_norm')
        {
            $vista_actual = "0";
            $text_unidad_familiar = 'NUEVA UNIDAD FAMILIAR<br />';
        }
    
        if($vista_actual == '0')
        {
            $clase_contenido_extra = 'contenido_formulario-nopasos';
            $clase_navbar_extra = 'navbar-nopasos';
        }
        else
        {
            $clase_contenido_extra = '';
            $clase_navbar_extra = '';
        }
    ?>

<form id="FormularioRegistroLista" method="post">
    
    <input type="hidden" name="estructura_id" id="estructura_id" value="<?php echo $estructura_id; ?>" />
    <input type="hidden" name="codigo_rubro" id="codigo_rubro" value="<?php echo $codigo_rubro; ?>" />
    <input type="hidden" name="vista_actual" id="vista_actual" value="<?php if($tipo_registro == 'nuevo_norm') { echo 'view_norm_datos_generales'; } else { echo $vista_actual; } ?>" />
    <input type="hidden" name="home_ant_sig" id="home_ant_sig" value="" />
    
    <input type="hidden" name="tipo_registro" id="tipo_registro" value="<?php echo $tipo_registro; ?>" />
    <input type="hidden" name="ejecutivo_id" id="ejecutivo_id" value="<?php echo $arrRespuesta[0]['ejecutivo_id']; ?>" />
    
    <nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_cobranzas->getContenidoNavApp($estructura_id, $vista_actual, "unidad_familiar"); ?> </nav>

    <div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div id="direccion_panel">
            
            <div style="text-align: center;"><label style="padding-bottom: 0px;" class="label-campo texto-centrado panel-heading" for="">DIRECCIONES</label></div>
            
            <div style="text-align: right; padding-right: 10px;">
                <span onclick="FormularioDireccion(<?php echo '-1, -1, ' . $tipo_persona_id . ', ' . $codigo_registro; ?>);" class="btn btn-primary" style="border-radius: 0px !important; font-size: 1em; padding: 5px 0px !important; width: 145px;">
                    <strong> <i class="fa fa-plus-circle" aria-hidden="true"></i> NUEVA DIRECCIÓN</strong>
                </span>
            </div>
            
            <br />
            
            <?php

            $mostrar = false;

            if(count($arrDirecciones[0]) > 0)
            {
                $mostrar = true;
            ?>
                <div style="padding: 0px 1px; overflow-x: auto; display: block;">

                    <table class="tblListas Centrado" style="width: 100% !important;" border="0">

                        <tr class="FilaGris">

                            <th style="width: 5%; font-weight: bold; text-align: center; font-size: 1.2em;">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </th>

                            <th style="width: 5%; font-weight: bold; text-align: center;">
                                Tipo Dir.
                            </th>

                            <th style="width: 70%; font-weight: bold; text-align: center;">
                                Dirección
                            </th>

                            <th style="width: 20%; font-weight: bold; text-align: center; font-style: italic;">
                                <?php echo $this->lang->line('rd_referencia'); ?>
                            </th>

                        </tr>

                        <?php

                        if ($mostrar) 
                        {
                            $i = 1;

                            $strClase = "FilaBlanca";

                            foreach ($arrDirecciones as $key => $value) 
                            {
                        ?>
                                <tr class="FilaBlanca">

                                    <td style="font-weight: bold; text-align: center;">
                                        <button onclick="FormularioDireccion(<?php echo $i . ', ' . $value['rd_id'] . ', ' . $tipo_persona_id . ', ' . $codigo_registro; ?>);" type="button" class="btn btn-primary" style="font-size: 1em; border-radius: 0px !important; padding: 12px 4px !important; width: 100% !important; max-width: 40px;">
                                            <strong> #<?php echo $i++; ?> </strong>
                                        </button>
                                    </td>

                                    <td style="font-weight: normal; text-align: center; font-style: italic;">
                                        <?php echo $value["rd_tipo"]; ?>
                                    </td>

                                    <td style="font-weight: normal; text-align: left; font-style: italic; font-size: 0.90em;">

                                        <?php

                                            echo "<div class='col-sm-3'><b>" . $this->lang->line('rd_dir_departamento') . ": </b>" . $value["rd_dir_departamento"] . "</div>";

                                            echo "<div class='col-sm-3'><b>" . $this->lang->line('rd_dir_localidad_ciudad') . ": </b>" . $value["rd_dir_localidad_ciudad"] . "</div>";

                                            $arrayAux = array();

                                            $aux_ele = $value["rd_cod_barrio_codigo"];
                                            if((int)$aux_ele > 0) { array_push($arrayAux, $value["rd_cod_barrio"]); }

                                            $aux_ele = $value["rd_direccion"];
                                            if(str_replace(' ', '', $aux_ele) != '') { array_push($arrayAux, $aux_ele); }

                                            $aux_ele = $value["rd_edificio"];
                                            if(str_replace(' ', '', $aux_ele) != '') { array_push($arrayAux, $aux_ele); }

                                            $aux_ele = $value["rd_numero"];
                                            if(str_replace(' ', '', $aux_ele) != '') { array_push($arrayAux, $aux_ele); }

                                            echo "<div class='col-sm-5'>" . implode(", ",$arrayAux) . "</div>";

                                        ?>
                                    </td>

                                    <td style="font-weight: normal; text-align: center; font-style: italic;">
                                        <?php echo $value["rd_referencia"] . ((int)$value["rd_referencia_codigo"]==2 && str_replace(' ', '', $value["rd_croquis"])=='' ? '<br />(Vacío)' : ''); ?>
                                    </td>

                                </tr>

                        <?php
                            }
                        }
                        ?>

                    </table>

                </div>

            <?php

            }

            else
            {            
                echo '<br /><br /><div class="PreguntaConfirmar"> <i class="fa fa-meh-o" aria-hidden="true"></i> Aún No se Registró Información<br />Puede crear desde NUEVA DIRECCIÓN </div>';
            }

            ?>
            
        </div>
    
        <div id="direccion_formulario"> </div>
        
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>