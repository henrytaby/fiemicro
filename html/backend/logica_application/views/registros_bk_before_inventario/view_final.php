<script type="text/javascript">
<?php

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios();
    
    // Se llama a la función para obtener las estadisticas de tiempo del Registro de Rubro
    $calculo_tiempo = $this->mfunciones_generales->TiempoRegistroRubro($estructura_id);
?>
    
</script>

    <?php

        if($vista_actual == '0' || $vista_actual == 'view_final')
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
    
        <br /><br />
        
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <br />
        
        <div style="text-align: center;">
            <label class='label-campo texto-centrado panel-heading color-azul' for=''><i class="fa fa-check-circle" aria-hidden="true"></i> ¡Finalizó el registro de la información del Rubro! </label>
        </div>
        
        <div class='col-sm-6'>
        
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-clock-o" aria-hidden="true"></i> Estadísticas del Registro del Rubro</div>
                <div class="panel-body">
                    
                    <div class="container">
                        <div class="row">
                            <div class="col" style="text-align: justify;">
                                <label class='label-campo texto-centrado' for=''> Inicio del Registro </label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='label-campo texto-centrado' for=''> <?php echo $calculo_tiempo->aux_fecha_ini; ?> </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="text-align: justify;">
                                <label class='label-campo texto-centrado' for=''> Fin del Registro </label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='label-campo texto-centrado' for=''> <?php echo $calculo_tiempo->aux_fecha_fin; ?> </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="text-align: justify;">
                                <label class='label-campo texto-centrado' for=''> Horas Laborales </label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='label-campo texto-centrado' for=''> <?php echo $calculo_tiempo->calculo_tiempo; ?> </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="text-align: justify;">
                                <label class='label-campo texto-centrado' for=''> <br />Tiempo Asignado </label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='label-campo texto-centrado' for=''> <br /><?php echo $calculo_tiempo->tiempo_etapa; ?> </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="text-align: justify;">
                                <label class='label-campo texto-centrado' for=''> Resultado </label>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='label-campo texto-centrado' for=''> <?php echo $calculo_tiempo->calculo_estado; ?> </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
        
        <div class='col-sm-6'>
            
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-lightbulb-o" aria-hidden="true"></i> ¿Que puede hacer ahora?</div>
                <div class="panel-body">
                    
                     <div style="text-align: justify;">
                        <label class='label-campo texto-centrado' for=''><i class="fa fa-check-square-o" aria-hidden="true"></i> Si lo requiere, puede verificar cualquier paso (considere que las estadísticas y tiempos se actualizarán) </label>
                        <br /><label class='label-campo texto-centrado' for=''><i class="fa fa-check-square-o" aria-hidden="true"></i> Volver al Master de Navegación, para continuar con el registro y/o realizar las acciones correspondientes. </label>
                    </div>
                    
                </div>
            </div>
            
        </div>
        
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>