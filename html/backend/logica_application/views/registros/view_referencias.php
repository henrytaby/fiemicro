<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios('sin_combo');
    
    if($tipo_registro == 'unidad_familiar')
    {
        echo '$(".informacion_operacion").hide();';
    }
?>

    $(document).ready(function() {
        
        $("div.modal-backdrop").remove();
        
        $(document).ready(function(){ 
            $('.informacion_general').find("input[type=text]").each(function(ev)
            {
                $(this).attr("placeholder", " :: Registrar ::");
            });
            
        });
    });

    $('#rp_nombres').attr("style","text-transform: capitalize;");
    $('#rp_primer_apellido').attr("style","text-transform: capitalize;");
    $('#rp_segundo_apellido').attr("style","text-transform: capitalize;");
    $('#con_primer_nombre').attr("style","text-transform: capitalize;");
    $('#con_segundo_nombre').attr("style","text-transform: capitalize;");
    $('#con_primera_pellido').attr("style","text-transform: capitalize;");
    $('#con_segundoa_pellido').attr("style","text-transform: capitalize;");

</script>

    <?php

        $text_unidad_familiar = '';
        if($tipo_registro == 'unidad_familiar')
        {
            $vista_actual = "0";
            $text_unidad_familiar = 'NUEVA UNIDAD FAMILIAR<br />';
        }
    
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
    <input type="hidden" name="vista_actual" id="vista_actual" value="<?php if($tipo_registro == 'unidad_familiar') { echo 'view_datos_generales'; } else { echo $vista_actual; } ?>" />
    <input type="hidden" name="home_ant_sig" id="home_ant_sig" value="" />
    
    <input type="hidden" name="tipo_registro" id="tipo_registro" value="<?php echo $tipo_registro; ?>" />
    <input type="hidden" name="ejecutivo_id" id="ejecutivo_id" value="<?php echo $arrRespuesta[0]['ejecutivo_id']; ?>" />
    
    <input type="hidden" name="general_categoria" id="general_categoria" value="1" />
    
    <input type="hidden" name="di_estadocivil" id="di_estadocivil" value="<?php echo $arrRespuesta[0]['di_estadocivil']; ?>" />
    
    <nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_generales->getContenidoNavApp($estructura_id, $vista_actual, "unidad_familiar"); ?> </nav>

    <div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading">REFERENCIAS PERSONALES</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='rp_nombres'><?php echo $this->lang->line('rp_nombres'); ?>:</label><?php echo $arrCajasHTML['rp_nombres']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='rp_primer_apellido'><?php echo $this->lang->line('rp_primer_apellido'); ?>:</label><?php echo $arrCajasHTML['rp_primer_apellido']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='rp_segundo_apellido'><?php echo $this->lang->line('rp_segundo_apellido'); ?>:</label><?php echo $arrCajasHTML['rp_segundo_apellido']; ?></div></div>
                <div class='col-sm-4-aux' style="display: none;"><div class='form-group'><label class='label-campo' for='rp_direccion'><?php echo $this->lang->line('rp_direccion'); ?>:</label><?php echo $arrCajasHTML['rp_direccion']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='rp_notelefonicos'><?php echo $this->lang->line('rp_notelefonicos'); ?>:</label><?php echo $arrCajasHTML['rp_notelefonicos']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='rp_nexo_cliente'><?php echo $this->lang->line('rp_nexo_cliente'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('rp_nexo_cliente', $arrRespuesta[0]['rp_nexo_cliente'], 'rp_nexo_cliente'); ?></div></div>
                
            </div>
        </div>
        
        <?php
        if($arrRespuesta[0]['di_estadocivil'] == 'CA')
        {
        ?>
            <div class="panel panel-default informacion_general">
                <div class="panel-heading">CÓNYUGE</div>
                <div class="panel-body">

                    <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='con_primer_nombre'><?php echo $this->lang->line('con_primer_nombre'); ?>:</label><?php echo $arrCajasHTML['con_primer_nombre']; ?></div></div>
                    <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='con_segundo_nombre'><?php echo $this->lang->line('con_segundo_nombre'); ?>:</label><?php echo $arrCajasHTML['con_segundo_nombre']; ?></div></div>
                    <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='con_primera_pellido'><?php echo $this->lang->line('con_primera_pellido'); ?>:</label><?php echo $arrCajasHTML['con_primera_pellido']; ?></div></div>
                    <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='con_segundoa_pellido'><?php echo $this->lang->line('con_segundoa_pellido'); ?>:</label><?php echo $arrCajasHTML['con_segundoa_pellido']; ?></div></div>
                    <div class='col-sm-4-aux' style="display: none;"><div class='form-group'><label class='label-campo' for='con_acteconomica_principal'><?php echo $this->lang->line('con_acteconomica_principal'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('con_acteconomica_principal', $arrRespuesta[0]['con_acteconomica_principal'], 'ae_actividad_fie'); ?></div></div>

                </div>
            </div>
        
        <?php
        }
        else
        {
        ?>
            
            <div class='col-sm-12'style="text-align: center;"> <label class='label-campo texto-centrado' for=''> Seleccionó el Estado Civil: "<?php echo $this->mfunciones_generales->GetValorCatalogoDB($arrRespuesta[0]['di_estadocivil'], 'di_estadocivil'); ?>" <br /> No es requerido registrar la información del Cónyuge. </label><br /></div>
                
            <div style="clear: both"></div>
            
        <?php
        }
        ?>
        
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>