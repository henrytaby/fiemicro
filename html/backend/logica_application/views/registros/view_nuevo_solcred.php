<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", "../SolCred/Guardar",
            "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS");
    
<?php 

    echo $strValidacionJqValidate;
    
?>
    $(document).ready(function() {
        $("div.modal-backdrop").remove();
        $("#tipo_cuenta").togglebutton();
        
        $('.contenido_formulario').find("input[type=text]").each(function(ev)
        {
            $(this).attr("style","text-transform: capitalize;");
        });
    });
    
    function PreguntaAccion(mensaje, accion, valor="-1")
    {
        $("#pregunta_titulo").html(mensaje);
        $("#pregunta_opcion").modal();
        
        $("#pregunta_opcion").val(accion);
        $("#accion_valor").val(valor);
        
        document.getElementById("FormularioRegistroLista").scrollIntoView();
    }
    
    function RealizaAccion()
    {
        switch ($("#pregunta_opcion").val()) {
            case 'nuevo_solcred':

                $("#FormularioRegistroLista").submit();

                break;

            default: break;
        }
    }
    
    $('#general_solicitante').attr("style","text-transform: capitalize;");
    
</script>

<form id="FormularioRegistroLista" method="post">
    
    <input type="hidden" name="estructura_id" id="estructura_id" value="<?php echo $estructura_id; ?>" />
    
    <div class="contenido_formulario container" style="margin-top: 0px;">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class='col-sm-12'style="text-align: center;"> <label class='label-campo texto-centrado panel-heading' for=''><i class="fa fa-user-circle" aria-hidden="true"></i> Nueva Solicitud de Crédito </label></div>

        <div style="clear: both"></div>
        

        <div class="panel panel-default informacion_general">
            <div class="panel-heading">DATOS PERSONALES</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_ci'><?php echo $this->lang->line('sol_ci'); ?>:</label><?php echo $arrCajasHTML['sol_ci']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_complemento'><?php echo $this->lang->line('sol_complemento'); ?>:</label><?php echo $arrCajasHTML['sol_complemento']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_extension'><?php echo $this->lang->line('sol_extension'); ?>:</label><?php echo $this->mfunciones_microcreditos->ObtenerCatalogoSelectSol('sol_extension', $arrRespuesta[0]['sol_extension'], 'cI_lugar_emisionoextension', '-1', 'aux_ci_creditos'); ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_primer_nombre'><?php echo $this->lang->line('sol_primer_nombre'); ?>:</label><?php echo $arrCajasHTML['sol_primer_nombre']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_segundo_nombre'><?php echo $this->lang->line('sol_segundo_nombre'); ?>:</label><?php echo $arrCajasHTML['sol_segundo_nombre']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_primer_apellido'><?php echo $this->lang->line('sol_primer_apellido'); ?>:</label><?php echo $arrCajasHTML['sol_primer_apellido']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_segundo_apellido'><?php echo $this->lang->line('sol_segundo_apellido'); ?>:</label><?php echo $arrCajasHTML['sol_segundo_apellido']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_correo'><?php echo $this->lang->line('sol_correo'); ?>:</label><?php echo $arrCajasHTML['sol_correo']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_cel'><?php echo $this->lang->line('sol_cel'); ?>:</label><?php echo $arrCajasHTML['sol_cel']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_telefono'><?php echo $this->lang->line('sol_telefono'); ?>:</label><?php echo $arrCajasHTML['sol_telefono']; ?></div></div>
                
            </div>
        </div>
        
        <div style="clear: both"></div>
        
        <br /><br />
        
        <div class="row">
            <div class="col" style="text-align: center;">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 300px !important; font-size: 13px; height: 35px;" onclick="PreguntaAccion('Se procederá a Registrar una Nueva Solicitud de Crédito con los datos proporcionados. <br /><br /> ¿Seguro que quiere continuar?', 'nuevo_solcred')"><i class="fa fa-address-book-o" aria-hidden="true"></i> Registrar Nueva Solicitud de Crédito </button>
            </div>
        </div>
        
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>

<div class="modal fade" id="pregunta_opcion" role="dialog">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="pregunta_titulo">Seleccione para Continuar</h4>
        </div>
        <div class="modal-body">
            <div style="text-align: center;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 130px !important;">Cancelar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 150px !important;" onclick="RealizaAccion()">Si, Continuar</button>
            </div>
        </div>
    </div>
</div>
</div>