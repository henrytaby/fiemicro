<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios('sincombo');
?>

    $(document).ready(function() {
        
        $(document).ready(function(){ 
            $('.contenido_formulario').find("input[type=text]").each(function(ev)
            {
                $(this).attr("placeholder", " :: Registrar ::");
            });
            $('.contenido_formulario').find("input[type=tel]").each(function(ev)
            {
                $(this).attr("placeholder", " :: Registrar ::");
            });
           
            $("#sol_detalle").attr("placeholder", " :: Registrar ::");
        });
        
        
        
        $(document).ready(function() {
            $("#sol_moneda").togglebutton();
            $("div.modal-backdrop").remove();
        });
        
        $('.contenido_formulario').find("input[type=text]").each(function(ev)
        {
            $(this).attr("style","text-transform: capitalize;");
        });
        
    });

    
</script>

    <?php
    
        $text_unidad_familiar = '';
        if($tipo_registro == 'nuevo_solcredito')
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
    <input type="hidden" name="vista_actual" id="vista_actual" value="<?php if($tipo_registro == 'nuevo_solcredito') { echo 'view_datos_generales'; } else { echo $vista_actual; } ?>" />
    <input type="hidden" name="home_ant_sig" id="home_ant_sig" value="" />
    
    <input type="hidden" name="tipo_registro" id="tipo_registro" value="<?php echo $tipo_registro; ?>" />
    <input type="hidden" name="ejecutivo_id" id="ejecutivo_id" value="<?php echo $arrRespuesta[0]['ejecutivo_id']; ?>" />
    
    <nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_microcreditos->getContenidoNavApp($estructura_id, $vista_actual, "unidad_familiar"); ?> </nav>

    <div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading">DATOS DEL CRÉDITO SOLICITADO</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_monto'><?php echo $this->lang->line('sol_monto'); ?>:</label><?php echo $arrCajasHTML['sol_monto']; ?></div></div>
                <div class='col-sm-4-aux' style='text-align: center;'><div class='form-group'><label class='label-campo' for='sol_moneda'><?php echo $this->lang->line('sol_moneda'); ?>:</label><br /><?php echo $arrCajasHTML['sol_moneda']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_plazo'><?php echo $this->lang->line('sol_plazo'); ?>:</label><?php echo $arrCajasHTML['sol_plazo']; ?></div></div>
                <div class='col-sm-12'><div class='form-group'><label class='label-campo' for='sol_detalle'><?php echo $this->lang->line('sol_detalle'); ?>:</label><?php echo $arrCajasHTML['sol_detalle']; ?></div></div>
                
            </div>
        </div>
        
        
        
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>