<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios();
    
?>
    $('#extra_activos_actividades_secundarias').attr('disabled','disabled');
    $('#extra_pasivo_actividades_secundarias').attr('disabled','disabled');
    $('#extra_amortizacion_otras_deudas').attr('disabled','disabled');
    
    // Cada vez que cambie el valor de las cajas, se actualiza el valor
    $('.balance_totales input[type="number"]').on('keyup change', function(){
        balance_totales();
    });
    
    // Llamar a las funciones iniciales
    balance_totales();
    
    function balance_totales()
    {
        var total_activo_corriente = 
        parseFloat($("#extra_efectivo_caja").val() || 0) + 
        parseFloat($("#extra_ahorro_dpf").val() || 0) + 
        parseFloat($("#extra_cuentas_cobrar").val() || 0) + 
        parseFloat($("#extra_inventario").val() || 0) + 
        parseFloat($("#extra_otros_activos_corrientes").val() || 0);
        
        var total_activo_no_corriente =
        parseFloat($("#extra_activo_fijo").val() || 0) + 
        parseFloat($("#extra_otros_activos_nocorrientes").val() || 0);
        
        var total_activo_actividad_principal = total_activo_corriente + total_activo_no_corriente;
        
        var total_activo_cliente = 
        total_activo_actividad_principal + 
        parseFloat($("#extra_activos_actividades_secundarias").val() || 0) + 
        parseFloat($("#extra_inmuebles_terrenos").val() || 0) + 
        parseFloat($("#extra_bienes_hogar").val() || 0) + 
        parseFloat($("#extra_otros_activos_familiares").val() || 0);
        
        var total_pasivo_corriente =
        parseFloat($("#extra_cuentas_pagar_proveedores").val() || 0) + 
        parseFloat($("#extra_prestamos_financieras_corto").val() || 0) + 
        parseFloat($("#extra_cuentas_pagar_corto").val() || 0);
        
        var total_pasivo_actividad_principal = 
        total_pasivo_corriente + 
        parseFloat($("#extra_prestamos_financieras_largo").val() || 0) + 
        parseFloat($("#extra_cuentas_pagar_largo").val() || 0) ;
        
        var total_pasivo_cliente =
        total_pasivo_actividad_principal + 
        parseFloat($("#extra_pasivo_actividades_secundarias").val() || 0) + 
        parseFloat($("#extra_pasivo_familiar").val() || 0);
        
        var total_patrimonio_actividad = total_activo_actividad_principal - total_pasivo_actividad_principal;
        
        var total_patrimonio_cliente = total_activo_cliente - total_pasivo_cliente;
        
        var total_patrimonio_pasivo = total_activo_cliente;
        
        $("#total_activo_corriente").html("Bs. " + total_activo_corriente.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        $("#total_activo_no_corriente").html("Bs. " + total_activo_no_corriente.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        $("#total_activo_actividad_principal").html("Bs. " + total_activo_actividad_principal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        $("#total_activo_cliente").html("Bs. " + total_activo_cliente.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        $("#total_pasivo_corriente").html("Bs. " + total_pasivo_corriente.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        $("#total_pasivo_actividad_principal").html("Bs. " + total_pasivo_actividad_principal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        $("#total_pasivo_cliente").html("Bs. " + total_pasivo_cliente.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        $("#total_patrimonio_actividad").html("Bs. " + total_patrimonio_actividad.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        $("#total_patrimonio_cliente").html("Bs. " + total_patrimonio_cliente.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        $("#total_patrimonio_pasivo").html("Bs. " + total_patrimonio_pasivo.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
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
        
        <div class='col-sm-12' style="text-align: center;"> <label class='label-campo texto-centrado panel-heading' for=''>Información Adicional y Balance General</label></div>
        
        <div style="clear: both"></div>
        
        <div class="container">
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
                    <label class='label-campo' for='extra_amortizacion_otras_deudas'><?php echo ($codigo_rubro!=4 ? $this->lang->line('extra_amortizacion_otras_deudas') : $this->lang->line('extra_amortizacion_otras_deudas_transporte')); ?> <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Es la sumatoria de los registros con NO Rfto. de la lista de Pasivos." data-balloon-pos="right"> </span> </label>
                </div>
                <div class="col" style="text-align: justify;">
                    <?php echo $arrCajasHTML['extra_amortizacion_otras_deudas']; ?>
                </div>
            </div>
            
            <?php
            // Se muestran los campos si el rubro es diferente de Transporte
            if($codigo_rubro != 4)
            {
            ?>
            
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_cuota_maxima_credito'><?php echo $this->lang->line('extra_cuota_maxima_credito'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_cuota_maxima_credito']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_amortizacion_credito'><?php echo $this->lang->line('extra_amortizacion_credito'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_amortizacion_credito']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_endeudamiento_credito'><?php echo $this->lang->line('extra_endeudamiento_credito'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_endeudamiento_credito']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_personal_ocupado'><?php echo $this->lang->line('extra_personal_ocupado'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_personal_ocupado']; ?>
                    </div>
                </div>
            
            <?php
            }
            else
            {
            ?>
            
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_cuota_prestamo_solicitado'><?php echo $this->lang->line('extra_cuota_prestamo_solicitado'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_cuota_prestamo_solicitado']; ?>
                    </div>
                </div>
            
            <?php
            }
            ?>
            
        </div>
        
        <div style="clear: both"></div>
        
        <br />
        
        <div class="panel panel-default balance_totales" id="gastos_familiares">
            <div class="panel-heading">Balance General Activos</div>
            <div class="panel-body">
                
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
                        <label class='label-campo' for='extra_efectivo_caja'><?php echo $this->lang->line('extra_efectivo_caja'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_efectivo_caja']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_ahorro_dpf'><?php echo $this->lang->line('extra_ahorro_dpf'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_ahorro_dpf']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_cuentas_cobrar'><?php echo $this->lang->line('extra_cuentas_cobrar'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_cuentas_cobrar']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_inventario'><?php echo $this->lang->line('extra_inventario'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_inventario']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_otros_activos_corrientes'><?php echo $this->lang->line('extra_otros_activos_corrientes'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_otros_activos_corrientes']; ?>
                    </div>
                </div>

                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo texto-centrado' for=''>TOTAL ACTIVO CORRIENTE</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado' for='' id="total_activo_corriente"></label>
                    </div>

                </div>

                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_activo_fijo'><?php echo $this->lang->line('extra_activo_fijo'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_activo_fijo']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_otros_activos_nocorrientes'><?php echo $this->lang->line('extra_otros_activos_nocorrientes'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_otros_activos_nocorrientes']; ?>
                    </div>
                </div>

                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo texto-centrado' for=''>TOTAL ACTIVO NO CORRIENTE</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado' for='' id="total_activo_no_corriente"></label>
                    </div>

                </div>
                
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo texto-centrado' for=''>TOTAL ACTIVO DE LA ACTIVIDAD PRINCIPAL</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado' for='' id="total_activo_actividad_principal"></label>
                    </div>

                </div>

                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_activos_actividades_secundarias'><?php echo $this->lang->line('extra_activos_actividades_secundarias'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_activos_actividades_secundarias']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_inmuebles_terrenos'><?php echo $this->lang->line('extra_inmuebles_terrenos'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_inmuebles_terrenos']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_bienes_hogar'><?php echo $this->lang->line('extra_bienes_hogar'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_bienes_hogar']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_otros_activos_familiares'><?php echo $this->lang->line('extra_otros_activos_familiares'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_otros_activos_familiares']; ?>
                    </div>
                </div>

                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo texto-centrado panel-heading' for=''>TOTAL ACTIVOS DEL CLIENTE</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado panel-heading' for='' id="total_activo_cliente"></label>
                    </div>

                </div>
            </div>
        </div>
        
        <div class="panel panel-default balance_totales" id="gastos_familiares">
            <div class="panel-heading">Balance General Pasivos</div>
            <div class="panel-body">
                
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
                        <label class='label-campo' for='extra_cuentas_pagar_proveedores'><?php echo $this->lang->line('extra_cuentas_pagar_proveedores'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_cuentas_pagar_proveedores']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_prestamos_financieras_corto'><?php echo $this->lang->line('extra_prestamos_financieras_corto'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_prestamos_financieras_corto']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_cuentas_pagar_corto'><?php echo $this->lang->line('extra_cuentas_pagar_corto'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_cuentas_pagar_corto']; ?>
                    </div>
                </div>
                
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo texto-centrado' for=''>TOTAL PASIVO CORRIENTE</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado' for='' id="total_pasivo_corriente"></label>
                    </div>

                </div>
                
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_prestamos_financieras_largo'><?php echo $this->lang->line('extra_prestamos_financieras_largo'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_prestamos_financieras_largo']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_cuentas_pagar_largo'><?php echo $this->lang->line('extra_cuentas_pagar_largo'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_cuentas_pagar_largo']; ?>
                    </div>
                </div>
                
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo texto-centrado' for=''>TOTAL PASIVO DE LA ACTIVIDAD PRINCIPAL</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado' for='' id="total_pasivo_actividad_principal"></label>
                    </div>

                </div>
                
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_pasivo_actividades_secundarias'><?php echo $this->lang->line('extra_pasivo_actividades_secundarias'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_pasivo_actividades_secundarias']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="text-align: justify;">
                        <label class='label-campo' for='extra_pasivo_familiar'><?php echo $this->lang->line('extra_pasivo_familiar'); ?></label>
                    </div>
                    <div class="col" style="text-align: justify;">
                        <?php echo $arrCajasHTML['extra_pasivo_familiar']; ?>
                    </div>
                </div>
                
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo texto-centrado panel-heading' for=''>TOTAL PASIVO DEL CLIENTE</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado panel-heading' for='' id="total_pasivo_cliente"></label>
                    </div>

                </div>
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo texto-centrado panel-heading' for=''>PATRIMONIO DE LA ACTIVIDAD PRINCIPAL</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado panel-heading' for='' id="total_patrimonio_actividad"></label>
                    </div>

                </div>
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo texto-centrado panel-heading' for=''>PATRIMONIO DEL CLIENTE</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado panel-heading' for='' id="total_patrimonio_cliente"></label>
                    </div>

                </div>
                <div class="row">

                    <div class="col" style="text-align: justify;">
                        <label class='label-campo texto-centrado panel-heading' for=''>PATRIMONIO + PASIVO</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado panel-heading' for='' id="total_patrimonio_pasivo"></label>
                    </div>

                </div>
                
            </div>
        </div>
        
    </div>
        
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>