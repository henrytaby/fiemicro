<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios();
    
?>
    
    function Margen_Lista(codigo, tab) {
        var strParametros = "&estructura_id=" + codigo + "&tab=" + tab;
        Ajax_CargadoGeneralPagina('../Margen/Lista', 'Margen_Utilidad', "divErrorListaResultado", '', strParametros);
    }
    
    function Margen_Nuevo(estructura_id, producto_id, tab) {
        var strParametros = "&estructura_id=" + estructura_id + "&producto_id=" + producto_id + "&tab=" + tab;
        Ajax_CargadoGeneralPagina('../Margen/Form', 'divElementoFlotante', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
    
    function MostrarContenedor(tab){
        
        switch (tab) {
            case "1":

                $("#titulo_contenedor").html("Establezca el Margen de Utilidad");
                $("#boton_nuevo").hide();
                $("#titulo_porcentaje").fadeIn();

                break;

            case "2":

                $("#titulo_contenedor").html("Establezca el Inventario y/o Costos");
                $("#boton_nuevo").show();
                $("#titulo_porcentaje").hide();

                break;
                
            default:

                break;
        }
    }
    
    function DetalleProducto(estructura_id) {
        
        $("#ResultadoMargenUtilidad").hide();
        var strParametros = "&estructura_id=" + estructura_id;
        Ajax_CargadoGeneralPagina('../Costos/Lista', 'ResultadoDetalleProducto', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
    
    function MostrarTablaMargen()
    {
        $("#ResultadoDetalleProducto").hide();
        $("#ResultadoMargenUtilidad").fadeIn();
        
    }
    
    <?php echo 'Margen_Lista(' . $estructura_id . ', "1");'; ?>
    
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
    
    <nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_generales->getContenidoNavApp($estructura_id, $vista_actual); ?> </nav>

    <div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div id="ResultadoMargenUtilidad">
        
            <ul class="nav nav-tabs">
                <li class="active" onclick="MostrarContenedor('1')"><a id="tab1" class="panel-heading" data-toggle="tab" href="#tab_margen_utilidad"> <i class="fa fa-cube" aria-hidden="true"></i> Margen Utilidad</a></li>
                <li onclick="MostrarContenedor('2')"><a id="tab2" class="panel-heading" data-toggle="tab" href="#tab_inventario"> <i class="fa fa-cubes" aria-hidden="true"></i> Items </a></li>
            </ul>

            <br />

            <div class="panel panel-default">
                <div class="panel-heading" id="titulo_contenedor">Establezca el Margen de Utilidad</div>
                <div class="panel-body">

                    <div style="text-align: right; display: none;" id="boton_nuevo">

                        <span class="EnlaceSimple label-campo" onclick="Margen_Nuevo('<?php echo $estructura_id; ?>', '-1', '2');">
                            <strong> <i class="fa fa-plus-square" aria-hidden="true"></i> NUEVO REGISTRO </strong>
                        </span>

                        <br /><br />

                    </div>

                    <div id="Margen_Utilidad" style="padding: 0px 10px; overflow-x: auto;"></div>
                </div>
            </div>

            <div class="panel panel-default" id="titulo_porcentaje">
                <div class="panel-heading">Porcentaje de los Principales Productos Comercializados <span id="valor_porcentaje"></span></div>
                <div class="panel-body" style="text-align: center;">

                    <?php echo $arrCajasHTML['margen_utilidad_productos']; ?>

                    <br /><br />

                    <div class="row">

                        <div class="col" style="text-align: center;">
                            <label class='label-campo texto-centrado' for=''>Ingreso Total Bs.</label>
                        </div>
                        <div class="col" style="text-align: center;">
                            <label class='label-campo texto-centrado panel-heading' for='' id="ingreso_total">Sin Selección</label>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col" style="text-align: center;">
                            <label class='label-campo texto-centrado' for=''>% Seleccionado</label>
                        </div>
                        <div class="col" style="text-align: center;">
                            <label class='label-campo texto-centrado panel-heading' for='' id="porcentaje_seleccionado">Sin Selección</label>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col" style="text-align: center;">
                            <label class='label-campo texto-centrado' for=''>Proy. Ventas Bs.</label>
                        </div>
                        <div class="col" style="text-align: center;">
                            <label class='label-campo texto-centrado panel-heading' for='' id="proyeccion_ventas">Sin Selección</label>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div id="ResultadoDetalleProducto"> </div>
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>

<script type="text/javascript">
    function TotalSumaMargen() {
        
        var ingreso_total = 0;
        var valor = 0;
        var calculo = 0;
        
        switch ($('#margen_utilidad_productos option:selected').val()) {
                case "1": valor=40; break;
                case "2": valor=50; break;
                case "3": valor=60; break;
                case "4": valor=70; break;
                case "5": valor=80; break;
                case "6": valor=100; break;

                default: valor=0; break;
        }
        
        if(valor != "0")
        {
            calculo = (parseFloat($("#total_suma_frecuencia").val() || 0) / (valor/100)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ',');
            ingreso_total = parseFloat($("#total_suma_frecuencia").val() || 0).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ',');
        }
        else
        {
            calculo = "0.00";
            ingreso_total = "0.00";
        }
        
        $("#ingreso_total").html(ingreso_total);
        $("#porcentaje_seleccionado").html(valor + "%");
        $("#proyeccion_ventas").html(calculo);
    }
    
    $(function(){
        
        $("#margen_utilidad_productos").on('change', function(){
            TotalSumaMargen();
        });
    });
    
</script>