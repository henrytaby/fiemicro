<script type="text/javascript">
    
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", '../MargenTransporte/Guardar',
            'Margen_Utilidad', 'divErrorListaResultado');
    
    $(document).ready(function() {
        $("#proveedor_frecuencia_dias").togglebutton();
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
    
    function EliminarMargenTransporte(estructura_id, margen_id) {
        var tab = $("#tab").val();
        var strParametros = "&estructura_id=" + estructura_id + "&margen_id=" + margen_id + "&tab=" + tab;
        Ajax_CargadoGeneralPagina('../MargenTransporte/Eliminar', 'Margen_Utilidad', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
    
</script>

<div class="container" style="overflow-y: auto; overflow-x: hidden; height: 375px;">
    
    <form id="FormularioRegistroLista" method="post" style="margin-bottom: 80px;">
    
        <input type="hidden" name="estructura_id" value="<?php if(isset($estructura_id)){ echo $estructura_id; } ?>" />
        <input type="hidden" name="margen_id" value="<?php if(isset($margen_id)){ echo $margen_id; } ?>" />
        <input type="hidden" name="tab" id="tab" value="<?php if(isset($tab)){ echo $tab; } ?>" />
        
        <div class='col-sm-12'>
            <div class="container" style="margin-top: 5px;">
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='margen_nombre'><?php echo ($tab==0 ? 'EGRESOS' : 'INGRESOS'); ?></label>
                        <br/>
                        <?php echo $arrCajasHTML['margen_nombre']; ?>
                    </div>
                </div>
                
            </div>
        </div>
        
        <div class='col-sm-6'>
            <div class="container" style="margin-top: 5px;">
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='margen_cantidad'><?php echo $this->lang->line('margen_cantidad'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['margen_cantidad']; ?>
                    </div>
                </div>
                
            </div>
        </div>
        
        <div class='col-sm-6'>
            <div class="container" style="margin-top: 5px;">
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='margen_unidad_medida'><?php echo $this->lang->line('margen_unidad_medida'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['margen_unidad_medida']; ?>
                    </div>
                </div>
                
            </div>
        </div>
        
        <div class='col-sm-6' style="display: <?php echo ($tab==1 ? 'block;' : 'none;'); ?>">
            <div class="container" style="margin-top: 5px;">
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='margen_pasajeros'><?php echo $this->lang->line('margen_pasajeros'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['margen_pasajeros']; ?>
                    </div>
                </div>

            </div>
        </div>
        
        <div class='col-sm-6'>
            <div class="container" style="margin-top: 5px;">
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='margen_monto_unitario'><?php echo ($tab==0 ? 'Costo' : 'Precio'); ?> Unitario</label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['margen_monto_unitario']; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <br /><br />
        
        
        
        <div class='col-sm-12' id="divOpciones">

            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <?php
                    
                    if($margen_id != -1)
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
                        <span class="nav-borrar" onclick="EliminarMargenTransporte('<?php if(isset($arrRespuesta[0]['prospecto_id'])) { echo $arrRespuesta[0]['prospecto_id']; } ?>', '<?php if(isset($arrRespuesta[0]['margen_id'])) { echo $arrRespuesta[0]['margen_id']; } ?>');"> ELIMINAR </span>
                    </div>

                </div>
                
            </div>
            
        </div>
        
    </form>
        
</div>