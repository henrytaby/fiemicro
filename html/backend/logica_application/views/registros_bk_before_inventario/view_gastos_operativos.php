<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios();
    
?>
    
    $(document).ready(function(){ 
        $('#gastos_operativos').find("input[type=text]").each(function(ev)
        {
            $(this).attr("placeholder", " :: Campo libre a utilizar ::");
        });
    });
    
    // Cada vez que cambie el valor de las cajas, se actualiza el valor
    $('.gastos_alq input[type="number"]').on('keyup change', function(){
        gastos_alq();
    });

    $('.gastos_salarios input[type="number"]').on('keyup change', function(){
        gastos_salarios();
    });

    $('.gastos_otros input[type="number"]').on('keyup change', function(){
        gastos_otros();
    });
    
    $('.gastos_operativos input[type="number"]').on('keyup change', function(){
        gastos_operativos();
    });

    // Llamar a las funciones iniciales
    gastos_alq();
    gastos_salarios();
    gastos_otros();
    gastos_operativos();
    
    function gastos_alq()
    {
        var suma_total = 
        parseFloat($("#operativos_alq_energia_monto").val() || 0) + 
        parseFloat($("#operativos_alq_agua_monto").val() || 0) + 
        parseFloat($("#operativos_alq_internet_monto").val() || 0) + 
        parseFloat($("#operativos_alq_combustible_monto").val() || 0) + 
        parseFloat($("#operativos_alq_libre1_monto").val() || 0) + 
        parseFloat($("#operativos_alq_libre2_monto").val() || 0);

        $("#gasto_alq").html("Bs. " + suma_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
    }
    
    function gastos_salarios()
    {
        var suma_total = 
        parseFloat($("#operativos_sal_aguinaldos_monto").val() || 0) + 
        parseFloat($("#operativos_sal_libre1_monto").val() || 0) + 
        parseFloat($("#operativos_sal_libre2_monto").val() || 0) + 
        parseFloat($("#operativos_sal_libre3_monto").val() || 0) + 
        parseFloat($("#operativos_sal_libre4_monto").val() || 0);

        $("#gasto_salario").html("Bs. " + suma_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
    }
    
    function gastos_otros()
    {
        var suma_total = 
        parseFloat($("#operativos_otro_transporte_monto").val() || 0) + 
        parseFloat($("#operativos_otro_licencias_monto").val() || 0) + 
        parseFloat($("#operativos_otro_alimentacion_monto").val() || 0) + 
        parseFloat($("#operativos_otro_mant_vehiculo_monto").val() || 0) + 
        parseFloat($("#operativos_otro_mant_maquina_monto").val() || 0) + 
        parseFloat($("#operativos_otro_imprevistos_monto").val() || 0) + 
        parseFloat($("#operativos_otro_otros_monto").val() || 0) + 
        parseFloat($("#operativos_otro_libre1_monto").val() || 0) + 
        parseFloat($("#operativos_otro_libre2_monto").val() || 0) + 
        parseFloat($("#operativos_otro_libre3_monto").val() || 0) + 
        parseFloat($("#operativos_otro_libre4_monto").val() || 0) + 
        parseFloat($("#operativos_otro_libre5_monto").val() || 0);

        $("#gasto_otros").html("Bs. " + suma_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
    }
    
    function gastos_operativos()
    {
        var suma_total = 
        
        parseFloat($("#operativos_alq_energia_monto").val() || 0) + 
        parseFloat($("#operativos_alq_agua_monto").val() || 0) + 
        parseFloat($("#operativos_alq_internet_monto").val() || 0) + 
        parseFloat($("#operativos_alq_combustible_monto").val() || 0) + 
        parseFloat($("#operativos_alq_libre1_monto").val() || 0) + 
        parseFloat($("#operativos_alq_libre2_monto").val() || 0) + 
        
        parseFloat($("#operativos_sal_aguinaldos_monto").val() || 0) + 
        parseFloat($("#operativos_sal_libre1_monto").val() || 0) + 
        parseFloat($("#operativos_sal_libre2_monto").val() || 0) + 
        parseFloat($("#operativos_sal_libre3_monto").val() || 0) + 
        parseFloat($("#operativos_sal_libre4_monto").val() || 0) + 
        
        parseFloat($("#operativos_otro_transporte_monto").val() || 0) + 
        parseFloat($("#operativos_otro_licencias_monto").val() || 0) + 
        parseFloat($("#operativos_otro_alimentacion_monto").val() || 0) + 
        parseFloat($("#operativos_otro_mant_vehiculo_monto").val() || 0) + 
        parseFloat($("#operativos_otro_mant_maquina_monto").val() || 0) + 
        parseFloat($("#operativos_otro_imprevistos_monto").val() || 0) + 
        parseFloat($("#operativos_otro_otros_monto").val() || 0) + 
        parseFloat($("#operativos_otro_libre1_monto").val() || 0) + 
        parseFloat($("#operativos_otro_libre2_monto").val() || 0) + 
        parseFloat($("#operativos_otro_libre3_monto").val() || 0) + 
        parseFloat($("#operativos_otro_libre4_monto").val() || 0) + 
        parseFloat($("#operativos_otro_libre5_monto").val() || 0);

        $("#gasto_operativos").html("Bs. " + suma_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
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

    <div id="gastos_operativos" class="contenido_formulario container gastos_operativos <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class='col-sm-12'style="text-align: center;"> <label class='label-campo texto-centrado panel-heading' for=''>Registre los Gastos Operativos</label></div>
        
        <div style="clear: both"></div>
        
        <div class="panel panel-default">
            <div class="panel-heading">Servicios Básicos, Comunicaciones y Alquileres</div>
            <div class="panel-body">

                <div class="container gastos_alq">
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
                            <label class='label-campo' for='operativos_alq_energia_monto'><?php echo $this->lang->line('operativos_alq_energia_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_alq_energia_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='operativos_alq_agua_monto'><?php echo $this->lang->line('operativos_alq_agua_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_alq_agua_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='operativos_alq_internet_monto'><?php echo $this->lang->line('operativos_alq_internet_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_alq_internet_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='operativos_alq_combustible_monto'><?php echo $this->lang->line('operativos_alq_combustible_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_alq_combustible_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_alq_libre1_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_alq_libre1_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_alq_libre2_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_alq_libre2_monto']; ?>
                        </div>
                    </div>
                    
                    <div class="row">

                        <div class="col" style="text-align: justify;">
                            <label class='label-campo texto-centrado' for=''>TOTAL</label>
                        </div>
                        <div class="col" style="text-align: center;">
                            <label class='label-campo texto-centrado' for='' id="gasto_alq"></label>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">Salarios</div>
            <div class="panel-body">

                <div class="container gastos_salarios">
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
                            <label class='label-campo' for='operativos_sal_aguinaldos_monto'><?php echo $this->lang->line('operativos_sal_aguinaldos_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_sal_aguinaldos_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_sal_libre1_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_sal_libre1_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_sal_libre2_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_sal_libre2_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_sal_libre3_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_sal_libre3_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_sal_libre4_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_sal_libre4_monto']; ?>
                        </div>
                    </div>
                    
                    <div class="row">

                        <div class="col" style="text-align: justify;">
                            <label class='label-campo texto-centrado' for=''>TOTAL</label>
                        </div>
                        <div class="col" style="text-align: center;">
                            <label class='label-campo texto-centrado' for='' id="gasto_salario"></label>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">Otros</div>
            <div class="panel-body">

                <div class="container gastos_otros">
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
                            <label class='label-campo' for='operativos_otro_transporte_monto'><?php echo $this->lang->line('operativos_otro_transporte_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_transporte_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='operativos_otro_licencias_monto'><?php echo $this->lang->line('operativos_otro_licencias_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_licencias_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='operativos_otro_alimentacion_monto'><?php echo $this->lang->line('operativos_otro_alimentacion_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_alimentacion_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='operativos_otro_mant_vehiculo_monto'><?php echo $this->lang->line('operativos_otro_mant_vehiculo_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_mant_vehiculo_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='operativos_otro_mant_maquina_monto'><?php echo $this->lang->line('operativos_otro_mant_maquina_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_mant_maquina_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='operativos_otro_imprevistos_monto'><?php echo $this->lang->line('operativos_otro_imprevistos_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_imprevistos_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <label class='label-campo' for='operativos_otro_otros_monto'><?php echo $this->lang->line('operativos_otro_otros_monto'); ?></label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_otros_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_libre1_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_libre1_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_libre2_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_libre2_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_libre3_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_libre3_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_libre4_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_libre4_monto']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_libre5_texto']; ?>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php echo $arrCajasHTML['operativos_otro_libre5_monto']; ?>
                        </div>
                    </div>
                    
                    <div class="row">

                        <div class="col" style="text-align: justify;">
                            <label class='label-campo texto-centrado' for=''>TOTAL</label>
                        </div>
                        <div class="col" style="text-align: center;">
                            <label class='label-campo texto-centrado' for='' id="gasto_otros"></label>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="container">
            
            <div class="row">

                <div class="col" style="text-align: justify;">
                    <label class='label-campo texto-centrado panel-heading' for=''>TOTAL GENERAL</label>
                </div>
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado panel-heading' for='' id="gasto_operativos"></label>
                </div>

            </div>
        </div>
        
    </div>
        
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>