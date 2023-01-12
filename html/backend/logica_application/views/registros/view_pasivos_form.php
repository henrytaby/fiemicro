<script type="text/javascript">
    
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", '../Pasivos/Guardar',
            'Lista_Pasivos', 'divErrorListaResultado');
    
    $(document).ready(function() {
        $("#pasivo_rfto").togglebutton();
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
    
    function EliminarPasivos(estructura_id, pasivos_id) {
        var strParametros = "&estructura_id=" + estructura_id + "&pasivos_id=" + pasivos_id;
        Ajax_CargadoGeneralPagina('../Pasivos/Eliminar', 'Lista_Pasivos', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
    
</script>

<div class="container" style="overflow-y: auto; overflow-x: hidden; height: 375px;">
    
    <form id="FormularioRegistroLista" method="post" style="margin-bottom: 80px;">
    
        <input type="hidden" name="estructura_id" value="<?php if(isset($estructura_id)){ echo $estructura_id; } ?>" />
        <input type="hidden" name="pasivos_id" value="<?php if(isset($pasivos_id)){ echo $pasivos_id; } ?>" />
        
        <div class='col-sm-6'>
            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='pasivo_acreedor'><?php echo $this->lang->line('pasivo_acreedor'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['pasivo_acreedor']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div class='col-sm-6'>
            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='pasivo_tipo'><?php echo $this->lang->line('pasivo_tipo'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['pasivo_tipo']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div class='col-sm-6'>
            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='pasivo_saldo'><?php echo $this->lang->line('pasivo_saldo'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['pasivo_saldo']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div class='col-sm-6'>
            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='pasivo_periodo'><?php echo $this->lang->line('pasivo_periodo'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['pasivo_periodo']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div class='col-sm-6'>
            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='pasivo_cuota_periodica'><?php echo $this->lang->line('pasivo_cuota_periodica'); ?> <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Este campo no está sujeto a cálculo." data-balloon-pos="right"> </span> </label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['pasivo_cuota_periodica']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div class='col-sm-6'>
            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='pasivo_cuota_mensual'><?php echo $this->lang->line('pasivo_cuota_mensual'); ?> <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Este campo no está sujeto a cálculo." data-balloon-pos="right"> </span> </label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['pasivo_cuota_mensual']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div class='col-sm-6'>
            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='pasivo_rfto'><?php echo $this->lang->line('pasivo_rfto'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['pasivo_rfto']; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <div class='col-sm-6'>
            <div class="container" style="margin-top: 5px;">
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='pasivo_destino'><?php echo $this->lang->line('pasivo_destino'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['pasivo_destino']; ?>
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
                    
                    if($pasivos_id != -1)
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
                        <span class="nav-borrar" onclick="EliminarPasivos('<?php if(isset($arrRespuesta[0]['prospecto_id'])) { echo $arrRespuesta[0]['prospecto_id']; } ?>', '<?php if(isset($arrRespuesta[0]['pasivo_id'])) { echo $arrRespuesta[0]['pasivo_id']; } ?>');"> ELIMINAR </span>
                    </div>

                </div>
                
            </div>
            
        </div>
        
    </form>
        
</div>