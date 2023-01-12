<script type="text/javascript">
    
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", '../Materia/Guardar',
            'Lista_Materia', 'divErrorListaResultado');
    
    $(document).ready(function() {
        $("#materia_frecuencia").togglebutton();
        $("div.modal-backdrop").remove();
    });
    
    $("#divOpciones").show();
    $("#confirmacion").hide();

    function MostrarConfirmacion()
    {
        $("#divOpciones").hide();
        $("#confirmacion").fadeIn(500);
    }
    
    function OcultarConfirmacion()
    {
        $("#divOpciones").fadeIn(500);
        $("#confirmacion").hide();
    }
    
    function EliminarMateria(estructura_id, materia_id) {
        var strParametros = "&estructura_id=" + estructura_id + "&materia_id=" + materia_id;
        Ajax_CargadoGeneralPagina('../Materia/Eliminar', 'Lista_Materia', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
    
</script>

<div class="container" style="overflow-y: auto; overflow-x: hidden; height: 375px;">
    
    <form id="FormularioRegistroLista" method="post" style="margin-bottom: 80px;">
    
        <input type="hidden" name="estructura_id" value="<?php if(isset($estructura_id)){ echo $estructura_id; } ?>" />
        <input type="hidden" name="materia_id" value="<?php if(isset($materia_id)){ echo $materia_id; } ?>" />
        
        <div class='col-sm-6'><div class='form-group'><label class='label-campo label-campo_amplio' for='materia_nombre'><?php echo $this->lang->line('materia_nombre'); ?></label><?php echo $arrCajasHTML['materia_nombre']; ?><br /><label class='label-campo label-campo_amplio' for=''>Frecuencia en Días</label><br /><?php echo $arrCajasHTML['materia_frecuencia']; ?></div></div>
        
        <div class='col-sm-6'>

            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo label-campo_amplio' for='materia_unidad_compra'><?php echo $this->lang->line('materia_unidad_compra'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['materia_unidad_compra']; ?>
                    </div>

                </div>
                
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo label-campo_amplio' for='materia_unidad_compra_cantidad'><?php echo $this->lang->line('materia_unidad_compra_cantidad'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['materia_unidad_compra_cantidad']; ?>
                    </div>

                </div>
                
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo label-campo_amplio' for='materia_unidad_uso'><?php echo $this->lang->line('materia_unidad_uso'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['materia_unidad_uso']; ?>
                    </div>

                </div>
                
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo label-campo_amplio' for='materia_unidad_uso_cantidad'><?php echo $this->lang->line('materia_unidad_uso_cantidad'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['materia_unidad_uso_cantidad']; ?>
                    </div>

                </div>
                
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo label-campo_amplio' for='materia_unidad_proceso'><?php echo $this->lang->line('materia_unidad_proceso'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['materia_unidad_proceso']; ?>
                    </div>

                </div>
                
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo label-campo_amplio' for='materia_producto_medida'><?php echo $this->lang->line('materia_producto_medida'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['materia_producto_medida']; ?>
                    </div>

                </div>
                
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo label-campo_amplio' for='materia_producto_medida_cantidad'><?php echo $this->lang->line('materia_producto_medida_cantidad'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['materia_producto_medida_cantidad']; ?>
                    </div>

                </div>
                
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo label-campo_amplio' for='xxx'><?php echo $this->lang->line('materia_precio_unitario'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['materia_precio_unitario']; ?>
                    </div>

                </div>
                
            </div>

        </div>
        
        <div class='col-sm-12'><div class='form-group'><label class='label-campo label-campo_amplio' for='materia_aclaracion'><?php echo $this->lang->line('materia_aclaracion'); ?>:</label><?php echo $arrCajasHTML['materia_aclaracion']; ?></div></div>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <br /><br />
        
        
        
        <div class='col-sm-12' id="divOpciones">

            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <?php
                    
                    if($materia_id != -1)
                    {
                        echo '
                            
                            <div class="col" style="text-align: center;">
                                <span class="nav-borrar" id="" onclick="MostrarConfirmacion();"> ELIMINAR </span>
                            </div>
                            
                            ';
                    }
                    
                    ?>
                    
                    <div class="col" style="text-align: center;">
                        <span class="nav-avanza" id="btnGuardarDatosLista"> GUARDAR </span>
                    </div>

                </div>
                
            </div>
            
        </div>
        
        <div class='col-sm-12' id="confirmacion" style="text-align: center;">
            
            <span class="texto-borrar">¿Confirma la acción de Eliminar?</span><br /><br />
            
            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: center;">
                        <span class="nav-borrar" id="" onclick="OcultarConfirmacion();">Cancelar </span>
                    </div>
                    
                    <div class="col" style="text-align: center;">
                        <span class="nav-borrar" onclick="EliminarMateria('<?php if(isset($arrRespuesta[0]['prospecto_id'])) { echo $arrRespuesta[0]['prospecto_id']; } ?>', '<?php if(isset($arrRespuesta[0]['materia_id'])) { echo $arrRespuesta[0]['materia_id']; } ?>');"> ELIMINAR </span>
                    </div>

                </div>
                
            </div>
            
        </div>
        
    
    </form>
        
</div>