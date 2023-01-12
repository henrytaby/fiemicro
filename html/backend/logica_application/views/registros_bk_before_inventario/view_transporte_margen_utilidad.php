<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios();
    
?>
    
    function MargenTransporte_Lista(codigo, tab) {
        var strParametros = "&estructura_id=" + codigo + "&tab=" + tab;
        Ajax_CargadoGeneralPagina('../MargenTransporte/Lista', 'Margen_Utilidad', "divErrorListaResultado", '', strParametros);
    }
    
    function MargenTransporte_Nuevo(estructura_id, margen_id, tab) {
        var strParametros = "&estructura_id=" + estructura_id + "&margen_id=" + margen_id + "&tab=" + tab;
        Ajax_CargadoGeneralPagina('../MargenTransporte/Form', 'divElementoFlotante', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
    
    function MostrarContenedor(tab){
        
        switch (tab) {
            case "1":

                $("#titulo_contenedor").html("ESTIMACIÓN DEL MARGEN DE UTILIDAD BRUTA<br />Información de Egresos");
                $("#titulo_porcentaje").fadeIn();

                break;

            case "2":

                $("#titulo_contenedor").html("ESTIMACIÓN DEL MARGEN DE UTILIDAD BRUTA<br />Información de Ingresos");
                $("#titulo_porcentaje").hide();

                break;
                
            default:

                break;
        }
    }
    
    function MostrarTablaMargen()
    {
        $("#ResultadoDetalleProducto").hide();
        $("#ResultadoMargenUtilidad").fadeIn();
        
    }
    
    <?php echo 'MargenTransporte_Lista(' . $estructura_id . ', "0");'; ?>
    
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
                <li class="active" onclick="MostrarContenedor('1')"><a id="tab1" class="panel-heading" data-toggle="tab" href="#tab_margen_egresos"> <i class="fa fa-angle-double-down" aria-hidden="true"></i> Info Egresos </a></li>
                <li onclick="MostrarContenedor('2')"><a id="tab2" class="panel-heading" data-toggle="tab" href="#tab_margen_ingresos"> <i class="fa fa-angle-double-up" aria-hidden="true"></i> Info Ingresos </a></li>
            </ul>

            <br />

            <div class="panel panel-default">
                <div class="panel-heading" id="titulo_contenedor" style="text-transform: uppercase;">ESTIMACIÓN DEL MARGEN DE UTILIDAD BRUTA<br />Información de Egresos</div>
                <div class="panel-body">
                    
                    <div id="Margen_Utilidad" style="padding: 0px 10px; overflow-x: auto;"></div>
                </div>
            </div>
            
            <div class="row">

                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado panel-heading' for=''>MARGEN DE UTILIDAD BRUTA</label>
                </div>
                <div class="col" style="text-align: center;">
                    <label class='label-campo texto-centrado panel-heading' for='' id="titulo_margen_utilidad_bruta">Sin Selección</label>
                </div>

            </div>
            
        </div>
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>