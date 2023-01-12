<script type="text/javascript">
    
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", '../OtrosIngresos/Guardar',
            'Lista_Otros_Ingresos', 'divErrorListaResultado');
    
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
    
    function EliminarOtros_Ingresos(estructura_id, otros_id) {
        var strParametros = "&estructura_id=" + estructura_id + "&otros_id=" + otros_id;
        Ajax_CargadoGeneralPagina('../OtrosIngresos/Eliminar', 'Lista_Otros_Ingresos', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
    
</script>

<div class="container" style="overflow-y: auto; overflow-x: hidden; height: 375px;">
    
    <form id="FormularioRegistroLista" method="post">
    
        <input type="hidden" name="estructura_id" value="<?php if(isset($estructura_id)){ echo $estructura_id; } ?>" />
        <input type="hidden" name="otros_id" value="<?php if(isset($otros_id)){ echo $otros_id; } ?>" />
        
        <div class='col-sm-6'>
            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='otros_descripcion_fuente'><?php echo $this->lang->line('otros_descripcion_fuente'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['otros_descripcion_fuente']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div class='col-sm-6'>
            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='otros_descripcion_respaldo'><?php echo $this->lang->line('otros_descripcion_respaldo'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['otros_descripcion_respaldo']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div class='col-sm-6'>
            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='otros_monto'><?php echo $this->lang->line('otros_monto'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['otros_monto']; ?>
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
                    
                    if($otros_id != -1)
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
                        <span class="nav-borrar" onclick="EliminarOtros_Ingresos('<?php if(isset($arrRespuesta[0]['prospecto_id'])) { echo $arrRespuesta[0]['prospecto_id']; } ?>', '<?php if(isset($arrRespuesta[0]['otros_id'])) { echo $arrRespuesta[0]['otros_id']; } ?>');"> ELIMINAR </span>
                    </div>

                </div>
                
            </div>
            
        </div>
        
    </form>
        
</div>