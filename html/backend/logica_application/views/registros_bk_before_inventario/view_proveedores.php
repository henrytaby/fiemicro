<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios();
    
?>
    
    function Proveedor_Lista(codigo) {
        var strParametros = "&estructura_id=" + codigo;
        Ajax_CargadoGeneralPagina('../Proveedor/Lista', 'Lista_Proveedor', "divErrorListaResultado", '', strParametros);
    }
    
    function Proveedor_Nuevo(estructura_id, proveedor_id) {
        var strParametros = "&estructura_id=" + estructura_id + "&proveedor_id=" + proveedor_id;
        Ajax_CargadoGeneralPagina('../Proveedor/Form', 'divElementoFlotante', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
    
    <?php echo 'Proveedor_Lista(' . $estructura_id . ');'; ?>
        
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
        
        <div class="panel panel-default">
            <div class="panel-heading">Compra a Principales Proveedores</div>
            <div class="panel-body">
            
                <div style="text-align: right;">
            
                    <span class="EnlaceSimple label-campo" onclick="Proveedor_Nuevo('<?php echo $estructura_id; ?>', '-1');">
                        <strong> <i class="fa fa-plus-square" aria-hidden="true"></i> NUEVO REGISTRO </strong>
                    </span>

                    <br /><br />

                </div>
                
                <div id="Lista_Proveedor" style="padding: 0px 10px; overflow-x: auto;"></div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">% DE CONCENTRACION DE COMPRAS EN PRINCIPALES PROVEEDORES</div>
            <div class="panel-body" style="text-align: center;">
            
                <?php echo $arrCajasHTML['porcentaje_participacion_proveedores']; ?>
                <br /><br />

                <div class="row">
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado' for=''>Promedio de Compras a Principales Proveedores</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado panel-heading' for='' id="promedio_compras">Sin Selección</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado' for=''>Compras Totales Inferidas a Partir de Conentración en Principales Proveedores</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado panel-heading' for='' id="compras_inferidas">Sin Selección</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado' for=''>Proyección de Ventas a Partir de Compras</label>
                    </div>
                    <div class="col" style="text-align: center;">
                        <label class='label-campo texto-centrado panel-heading' for='' id="proyeccion_ventas">Sin Selección</label>
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>

<script type="text/javascript">
    
    function obtener_valores()
    {
        var mub_total = <?php echo $this->mfunciones_generales->CalculoLead($estructura_id, 'extra_mub'); ?>;
        
        var promedio_mensual_total = parseFloat($("#promedio_mensual_total").val() || 0);
        
        var porcentaje_participacion_proveedores = 0;
        
        switch ($('#porcentaje_participacion_proveedores option:selected').val()) {
            case "1": porcentaje_participacion_proveedores=40; break;
            case "2": porcentaje_participacion_proveedores=50; break;
            case "3": porcentaje_participacion_proveedores=60; break;
            case "4": porcentaje_participacion_proveedores=70; break;
            case "5": porcentaje_participacion_proveedores=80; break;
            case "6": porcentaje_participacion_proveedores=100; break;

            default: porcentaje_participacion_proveedores=0; $("#compras_inferidas").html("SIN SELECCIÓN"); break;
        }
        
        $("#promedio_compras").html(promedio_mensual_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        
        var compras_inferidas = 0;
        if(porcentaje_participacion_proveedores != 0)
        {
            compras_inferidas = (promedio_mensual_total/porcentaje_participacion_proveedores)*100;
        }
        
        $("#compras_inferidas").html((compras_inferidas).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        
        var proyeccion_ventas = 0;
        if((1-mub_total) != 0)
        {
            proyeccion_ventas = compras_inferidas/(1-mub_total);
        }
        
        $("#proyeccion_ventas").html((proyeccion_ventas).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace('.', '|').replace(',', '.').replace('|', ','));
        
    }
    
    $(function(){
        
        $("#porcentaje_participacion_proveedores").on('change', function(){
            obtener_valores();
        });
    });
    
</script>