<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios('sin_combo');
    
?>
    
    $(document).ready(function() {
        $("#transporte_tipo_prestatario").togglebutton();
        $("#transporte_tipo_transporte").togglebutton();
        
        $('[name ="transporte_preg_trabaja_semana"]').togglebutton();
        $('[name ="transporte_preg_vehiculo_combustible"]').togglebutton();
        
        $("div.modal-backdrop").remove();
        transporte_campos();
    });
    
    $("#transporte_tipo_prestatario").on('change', function(){
        transporte_campos();
    });
    $("#transporte_tipo_transporte").on('change', function(){
        transporte_campos();
    });
    
    function transporte_campos()
    {
        var tipo_prestatario = $('#transporte_tipo_prestatario option:selected').val();
        var tipo_transporte = $('#transporte_tipo_transporte option:selected').val();

        $(".campo_transporte").show();
        $("#texto_transporte_preg_tiempo_vuelta").html("<?php echo $this->lang->line('transporte_preg_tiempo_vuelta'); ?>");

        switch (tipo_prestatario) {
            // PROPIETARIO QUE PERCIBE RENTA
            case '2':

                $("#transporte_preg_tiempo_no_trabaja").hide();
                $("#transporte_preg_tiempo_parada").hide();
                $("#transporte_preg_tiempo_vuelta").hide();

                break;

            default:

                break;
        }

        switch (tipo_transporte) {
            // RADIO TAXI y MOTO TAXI
            case '4':
            case '6':

                $("#transporte_preg_sindicato_lineas").hide();
                $("#transporte_preg_sindicato_grupos").hide();
                $("#transporte_preg_unidades_grupo").hide();
                $("#transporte_preg_grupo_rota").hide();
                $("#transporte_preg_lineas_buenas").hide();
                $("#transporte_preg_lineas_regulares").hide();
                $("#transporte_preg_lineas_malas").hide();
                $("#transporte_preg_tiempo_parada").hide();

                break;

            // TAXI
            case '5':

                $("#transporte_preg_sindicato").hide();
                $("#transporte_preg_sindicato_lineas").hide();
                $("#transporte_preg_sindicato_grupos").hide();
                $("#transporte_preg_unidades_grupo").hide();
                $("#transporte_preg_grupo_rota").hide();
                $("#transporte_preg_lineas_buenas").hide();
                $("#transporte_preg_lineas_regulares").hide();
                $("#transporte_preg_lineas_malas").hide();
                $("#transporte_preg_tiempo_parada").hide();

                break;

            default:

                break;
        }

        switch (tipo_transporte) {
            case '4':
            case '5':
            case '6':
                
                $("#texto_transporte_preg_tiempo_vuelta").html("¿Cuánto tiempo dura una carrera?");

                 break;

            default:

                break;
        }
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

    <div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class='col-sm-12'style="text-align: center;"> <label class='label-campo texto-centrado panel-heading' for=''>I. Información de la Fuente Generadora de Ingresos</label></div>
        
        <div style="clear: both"></div>
        
        <div class='col-sm-6' style="text-align: center;">
            <label class='label-campo' for=''> <?php echo $this->lang->line('transporte_tipo_prestatario'); ?> </label><br /><?php echo $arrCajasHTML['transporte_tipo_prestatario']; ?>
        </div>
        <div class='col-sm-6' style="text-align: center;">
            <label class='label-campo' for=''> <?php echo $this->lang->line('transporte_tipo_transporte'); ?> </label><br /><?php echo $arrCajasHTML['transporte_tipo_transporte']; ?>
        </div>
        
        <div style="clear: both"></div>
        
        <br />
        
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_sindicato">
            <label class='label-campo' for='transporte_preg_sindicato'> <?php echo $this->lang->line('transporte_preg_sindicato'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_sindicato']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_sindicato_lineas">
            <label class='label-campo' for='transporte_preg_sindicato_lineas'> <?php echo $this->lang->line('transporte_preg_sindicato_lineas'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_sindicato_lineas']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_sindicato_grupos">
            <label class='label-campo' for='transporte_preg_sindicato_grupos'> <?php echo $this->lang->line('transporte_preg_sindicato_grupos'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_sindicato_grupos']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_unidades_grupo">
            <label class='label-campo' for='transporte_preg_unidades_grupo'> <?php echo $this->lang->line('transporte_preg_unidades_grupo'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_unidades_grupo']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_grupo_rota">
            <label class='label-campo' for='transporte_preg_grupo_rota'> <?php echo $this->lang->line('transporte_preg_grupo_rota'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_grupo_rota']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_lineas_buenas">
            <label class='label-campo' for='transporte_preg_lineas_buenas'> <?php echo $this->lang->line('transporte_preg_lineas_buenas'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_lineas_buenas']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_lineas_regulares">
            <label class='label-campo' for='transporte_preg_lineas_regulares'> <?php echo $this->lang->line('transporte_preg_lineas_regulares'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_lineas_regulares']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_lineas_malas">
            <label class='label-campo' for='transporte_preg_lineas_malas'> <?php echo $this->lang->line('transporte_preg_lineas_malas'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_lineas_malas']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_trabaja_semana">
            <label class='label-campo' for='transporte_preg_trabaja_semana'> <?php echo $this->lang->line('transporte_preg_trabaja_semana'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_trabaja_semana']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_trabaja_dia">
            <label class='label-campo' for='transporte_preg_trabaja_dia'> <?php echo $this->lang->line('transporte_preg_trabaja_dia'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_trabaja_dia']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_jornada_inicia">
            <label class='label-campo' for='transporte_preg_jornada_inicia'> <?php echo $this->lang->line('transporte_preg_jornada_inicia'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_jornada_inicia']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_jornada_concluye">
            <label class='label-campo' for='transporte_preg_jornada_concluye'> <?php echo $this->lang->line('transporte_preg_jornada_concluye'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_jornada_concluye']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_tiempo_no_trabaja">
            <label class='label-campo' for='transporte_preg_tiempo_no_trabaja'> <?php echo $this->lang->line('transporte_preg_tiempo_no_trabaja'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_tiempo_no_trabaja']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_tiempo_parada">
            <label class='label-campo' for='transporte_preg_tiempo_parada'> <?php echo $this->lang->line('transporte_preg_tiempo_parada'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_tiempo_parada']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_tiempo_vuelta">
            <label class='label-campo' for='transporte_preg_tiempo_vuelta' id="texto_transporte_preg_tiempo_vuelta"> <?php echo $this->lang->line('transporte_preg_tiempo_vuelta'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_tiempo_vuelta']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_vehiculo_ano">
            <label class='label-campo' for='transporte_preg_vehiculo_ano'> <?php echo $this->lang->line('transporte_preg_vehiculo_ano'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_vehiculo_ano']; ?>
        </div>
        <div class='col-sm-6 campo_transporte' style="text-align: center;" id="transporte_preg_vehiculo_combustible">
            <label class='label-campo' for='transporte_preg_vehiculo_combustible'> <?php echo $this->lang->line('transporte_preg_vehiculo_combustible'); ?> </label><br /><?php echo $arrCajasHTML['transporte_preg_vehiculo_combustible']; ?>
        </div>
        
        
        <div style="clear: both"></div>
        
        <br />
        
    </div>
        
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>