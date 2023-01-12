<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios();
    
?>
    
    function Otros_Ingresos_Lista(codigo) {
        var strParametros = "&estructura_id=" + codigo;
        Ajax_CargadoGeneralPagina('../OtrosIngresos/Lista', 'Lista_Otros_Ingresos', "divErrorListaResultado", '', strParametros);
    }
    
    function Otros_Ingresos_Nuevo(estructura_id, otros_id) {
        var strParametros = "&estructura_id=" + estructura_id + "&otros_id=" + otros_id;
        Ajax_CargadoGeneralPagina('../OtrosIngresos/Form', 'divElementoFlotante', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }
    
    <?php echo 'Otros_Ingresos_Lista(' . $estructura_id . ');'; ?>
    
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
            <div class="panel-heading">OTROS INGRESOS</div>
            <div class="panel-body">
            
                <div style="text-align: right;">
            
                    <span class="EnlaceSimple label-campo" onclick="Otros_Ingresos_Nuevo('<?php echo $estructura_id; ?>', '-1');">
                        <strong> <i class="fa fa-plus-square" aria-hidden="true"></i> NUEVO REGISTRO </strong>
                    </span>

                    <br /><br />

                </div>
                
                <div id="Lista_Otros_Ingresos" style="padding: 0px 10px; overflow-x: auto;"></div>
            </div>
        </div>
        
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>