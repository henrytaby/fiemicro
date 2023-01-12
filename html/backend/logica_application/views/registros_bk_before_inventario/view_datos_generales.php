<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios();
    
    if($tipo_registro == 'unidad_familiar')
    {
        echo '$(".informacion_operacion").hide();';
    }
?>

    $('#general_solicitante').attr("style","text-transform: capitalize;");

</script>

    <?php

        $text_unidad_familiar = '';
        if($tipo_registro == 'unidad_familiar')
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
    <input type="hidden" name="vista_actual" id="vista_actual" value="<?php if($tipo_registro == 'unidad_familiar') { echo 'view_datos_generales'; } else { echo $vista_actual; } ?>" />
    <input type="hidden" name="home_ant_sig" id="home_ant_sig" value="" />
    
    <input type="hidden" name="tipo_registro" id="tipo_registro" value="<?php echo $tipo_registro; ?>" />
    <input type="hidden" name="ejecutivo_id" id="ejecutivo_id" value="<?php echo $arrRespuesta[0]['ejecutivo_id']; ?>" />
    
    <input type="hidden" name="general_categoria" id="general_categoria" value="<?php echo $arrRespuesta[0]['general_categoria']; ?>" />
    
    <nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_generales->getContenidoNavApp($estructura_id, $vista_actual, "unidad_familiar"); ?> </nav>

    <div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <?php
            if($tipo_registro == 'unidad_familiar')
            {
                $arrCampana = $this->mfunciones_logica->ObtenerCampana(-1);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCampana);
                
                if(isset($arrCampana[0]) && count($arrCampana[0]) > 0)
                {
                    echo '
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">SELECCIONE EL RUBRO</div>
                        <div class="panel-body" style="text-align: center;">
                            <label class="label-campo" for="" style="font-style: italic;"> ¡Esta acción no se puede deshacer! </label><br />' . html_select('camp_id', $arrCampana, 'camp_id', 'camp_nombre', '', '') . '
                        </div>
                    </div>
                    
                    <div style="clear: both"></div>

                    <br />';
                }
                else
                {
                    echo $this->lang->line('TablaNoRegistros');
                }
            }
        ?>
                
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading"><?php echo $text_unidad_familiar; ?>Datos generales del solicitante</div>
            <div class="panel-body">
                
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_solicitante'><?php echo $this->lang->line('general_solicitante'); ?>:</label><?php echo $arrCajasHTML['general_solicitante']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_telefono'><?php echo $this->lang->line('general_telefono'); ?>:</label><?php echo $arrCajasHTML['general_telefono']; ?></div></div>
                <!-- <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_email'><?php echo $this->lang->line('general_email'); ?>:</label><?php echo $arrCajasHTML['general_email']; ?></div></div> -->
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_direccion'><?php echo $this->lang->line('general_direccion'); ?>:</label><?php echo $arrCajasHTML['general_direccion']; ?></div></div>
                
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_actividad'><?php echo $this->lang->line('general_actividad'); ?>:</label><?php echo $arrCajasHTML['general_actividad']; ?></div></div>
                
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_ci'><?php echo $this->lang->line('general_ci'); ?>:</label><?php echo $arrCajasHTML['general_ci']; ?> <br /><br /> <?php echo $arrCajasHTML['general_ci_extension']; ?></div></div>
                
                <?php
                    if($arrRespuesta[0]['general_categoria'] == "1")
                    {
                        echo '
                
                        <div class="col-sm-6 informacion_operacion"><div class="form-group"><label class="label-campo" for="general_destino">' . $this->lang->line("general_destino") . ':</label>' . $arrCajasHTML["general_destino"] . '</div></div>
                            
                        <div class="col-sm-6 informacion_operacion"><div class="form-group"><label class="label-campo" for="general_comentarios">' . $this->lang->line("general_comentarios") . ':</label>' . $arrCajasHTML["general_comentarios"] . '</div></div>

                        <div class="col-sm-6 informacion_operacion" style="text-align: center;"><div class="form-group"><label class="label-campo" for="general_interes">' . $this->lang->line("general_interes") . ':</label><br />' . $arrCajasHTML["general_interes"] . '</div></div>

                        <div class="col-sm-6 informacion_operacion"><div class="form-group"><label class="label-campo" for="">' . $this->lang->line("general_productos") . ':</label> <br />';
                        
                        // Listado de Servicios
                        $arrActividades = $this->mfunciones_logica->ObtenerActividades(-1);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrActividades);

                        // Lista los perfiles disponibles

                        if (isset($arrActividades[0])) 
                        {
                            $i = 0;
                            
                            foreach ($arrActividades as $key => $value) 
                            {
                                $checked = '';
                                if($this->mfunciones_generales->GetActividadProspecto($estructura_id, $value["act_id"]))
                                {
                                    $checked = 'checked="checked"';
                                }

                                echo '<div class="divOpciones">';
                                echo '<input id="producto' . $i , '" type="checkbox" name="producto_list[]" '. $checked .' value="' . $value["act_id"] . '">';
                                echo '<label style="margin-bottom: 0px;" for="producto' . $i , '"><span></span>' . $value["act_detalle"] . '</label>';
                                echo '</div>';

                                $i++;
                            } 
                        }
                        
                        echo "</div></div>";
                    }
                ?>
                        
            </div>
        </div>
        
        <div class="panel panel-default informacion_operacion">
            <div class="panel-heading">Datos de la Operación</div>
            <div class="panel-body">
                
                <?php
                if($codigo_rubro != 4)
                {
                ?>
                
                    <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operacion_efectivo'><?php echo $this->lang->line('operacion_efectivo'); ?>:</label><?php echo $arrCajasHTML['operacion_efectivo']; ?></div></div>
                    <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='operacion_dias'><?php echo $this->lang->line('operacion_dias'); ?>:</label><?php echo $arrCajasHTML['operacion_dias']; ?></div></div>
                
                <?php
                }
                ?>
                    
                <div class='col-sm-6'>
                
                    <div class="container" style="margin-top: 5px;">
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                &nbsp;
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='label-campo texto-centrado' for=''>Meses</label>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='label-campo' for='operacion_antiguedad'><?php echo $this->lang->line('operacion_antiguedad'); ?>:</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['operacion_antiguedad']; ?>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='label-campo' for='operacion_tiempo'><?php echo $this->lang->line('operacion_tiempo'); ?>:</label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <?php echo $arrCajasHTML['operacion_tiempo']; ?>
                            </div>

                        </div>
                    </div>
                
                </div>
                
                <?php
                if($codigo_rubro != 4)
                {
                ?>
                    
                    <div class='col-sm-6'>

                        <div class="container" style="margin-top: 5px;">
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for=''>ACLARAR TIPO DE LAS VENTAS</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <label class='label-campo texto-centrado' for=''>DEFINIR PORCENTAJE</label>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='aclarar_contado'><?php echo $this->lang->line('aclarar_contado'); ?>:</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['aclarar_contado']; ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col" style="text-align: center;">
                                    <label class='label-campo' for='aclarar_credito'><?php echo $this->lang->line('aclarar_credito'); ?>:</label>
                                </div>
                                <div class="col" style="text-align: center;">
                                    <?php echo $arrCajasHTML['aclarar_credito']; ?>
                                </div>

                            </div>
                        </div>

                    </div>
                    
                <?php
                }
                ?>
                    
            </div>
        </div>
    
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>