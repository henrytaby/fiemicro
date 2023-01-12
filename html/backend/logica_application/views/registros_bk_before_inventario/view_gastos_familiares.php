<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios();
    
?>
    
    $(document).ready(function(){ 
        $('#gastos_familiares').find("input[type=text]").each(function(ev)
        {
            $(this).attr("placeholder", " :: Campo libre a utilizar ::");
        });
    });
    
    // Cada vez que cambie el valor de las cajas, se actualiza el valor
    $('.gastos_detalle input[type="number"]').on('keyup change', function(){
        gastos_detalle();
    });
    
    // Llamar a las funciones iniciales
    gastos_detalle();
    
    function gastos_detalle()
    {
        var suma_total = 
        parseFloat($("#familiar_alimentacion_monto").val() || 0) + 
        parseFloat($("#familiar_energia_monto").val() || 0) + 
        parseFloat($("#familiar_agua_monto").val() || 0) + 
        parseFloat($("#familiar_gas_monto").val() || 0) + 
        parseFloat($("#familiar_telefono_monto").val() || 0) + 
        parseFloat($("#familiar_celular_monto").val() || 0) + 
        parseFloat($("#familiar_internet_monto").val() || 0) + 
        parseFloat($("#familiar_tv_monto").val() || 0) + 
        parseFloat($("#familiar_impuestos_monto").val() || 0) + 
        parseFloat($("#familiar_alquileres_monto").val() || 0) + 
        parseFloat($("#familiar_educacion_monto").val() || 0) + 
        parseFloat($("#familiar_transporte_monto").val() || 0) + 
        parseFloat($("#familiar_salud_monto").val() || 0) + 
        parseFloat($("#familiar_empleada_monto").val() || 0) + 
        parseFloat($("#familiar_diversion_monto").val() || 0) + 
        parseFloat($("#familiar_vestimenta_monto").val() || 0) + 
        parseFloat($("#familiar_otros_monto").val() || 0) + 
        parseFloat($("#familiar_libre1_monto").val() || 0) + 
        parseFloat($("#familiar_libre2_monto").val() || 0) + 
        parseFloat($("#familiar_libre3_monto").val() || 0) + 
        parseFloat($("#familiar_libre4_monto").val() || 0) + 
        parseFloat($("#familiar_libre5_monto").val() || 0);

        $("#gasto_detalle").html("Bs. " + suma_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
    }
    
</script>

    <?php

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
    <input type="hidden" name="vista_actual" id="vista_actual" value="<?php echo $vista_actual; ?>" />
    <input type="hidden" name="home_ant_sig" id="home_ant_sig" value="" />
    <input type="hidden" name="sin_guardar" id="sin_guardar" value="0" />
    
    <nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_generales->getContenidoNavApp($estructura_id, $vista_actual); ?> </nav>

    <div class="contenido_formulario container gastos_familiares <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class='col-sm-12'style="text-align: center;"> <label class='label-campo texto-centrado panel-heading' for=''>Registre los Gastos Familiares</label></div>
        
        <div style="clear: both"></div>
        
        <div class="container">
            <div class="row">
                <div class="col" style="text-align: justify;">
                    <label class='label-campo' for='familiar_dependientes_ingreso'><?php echo $this->lang->line('familiar_dependientes_ingreso'); ?></label>
                </div>
                <div class="col" style="text-align: justify;">
                    <?php echo $arrCajasHTML['familiar_dependientes_ingreso']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: justify;">
                    <label class='label-campo' for='familiar_edad_hijos'><?php echo $this->lang->line('familiar_edad_hijos'); ?></label>
                </div>
                <div class="col" style="text-align: justify;">
                    <?php echo $arrCajasHTML['familiar_edad_hijos']; ?>
                </div>
            </div>
        </div>
        
        <div style="clear: both"></div>
        
        <br />
        
        <div class="panel panel-default" id="gastos_familiares">
            <div class="panel-heading">Gastos Mensuales</div>
            <div class="panel-body">

                <div class="container gastos_detalle">
                    <div class="row">

                        <div class="col" style="text-align: justify;">
                            <label class='label-campo texto-centrado' for=''>CRITERIO</label>
                        </div>
                        <div class="col" style="text-align: center;">
                            <label class='label-campo texto-centrado' for=''>IMPORTE BS.</label>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_alimentacion_monto'><?php echo $this->lang->line('familiar_alimentacion_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_alimentacion_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_energia_monto'><?php echo $this->lang->line('familiar_energia_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_energia_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_agua_monto'><?php echo $this->lang->line('familiar_agua_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_agua_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_gas_monto'><?php echo $this->lang->line('familiar_gas_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_gas_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_telefono_monto'><?php echo $this->lang->line('familiar_telefono_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_telefono_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_celular_monto'><?php echo $this->lang->line('familiar_celular_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_celular_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_internet_monto'><?php echo $this->lang->line('familiar_internet_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_internet_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_tv_monto'><?php echo $this->lang->line('familiar_tv_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_tv_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_impuestos_monto'><?php echo $this->lang->line('familiar_impuestos_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_impuestos_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_alquileres_monto'><?php echo $this->lang->line('familiar_alquileres_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_alquileres_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_educacion_monto'><?php echo $this->lang->line('familiar_educacion_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_educacion_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_transporte_monto'><?php echo $this->lang->line('familiar_transporte_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_transporte_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_salud_monto'><?php echo $this->lang->line('familiar_salud_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_salud_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_empleada_monto'><?php echo $this->lang->line('familiar_empleada_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_empleada_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_diversion_monto'><?php echo $this->lang->line('familiar_diversion_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_diversion_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_vestimenta_monto'><?php echo $this->lang->line('familiar_vestimenta_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_vestimenta_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='familiar_otros_monto'><?php echo $this->lang->line('familiar_otros_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_otros_monto']; ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_libre1_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_libre1_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_libre2_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_libre2_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_libre3_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_libre3_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_libre4_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_libre4_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_libre5_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['familiar_libre5_monto']; ?>
                        </div>
                    </div>
                    
                    <div class="row">

                        <div class="col" style="text-align: justify;">
                            <label class='label-campo texto-centrado' for=''>TOTAL</label>
                        </div>
                        <div class="col" style="text-align: center;">
                            <label class='label-campo texto-centrado' for='' id="gasto_detalle"></label>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        
    </div>
        
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>