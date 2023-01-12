<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", "../Norm/Guardar",
            "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS");
    
<?php 

    echo $strValidacionJqValidate;
    
?>
    $(document).ready(function() {
        $("div.modal-backdrop").remove();
        
        $('.contenido_formulario').find("input[type=text]").each(function(ev)
        {
            $(this).attr("style","text-transform: capitalize;");
        });
    });
    
    $('#registro_num_proceso').on('keyup change', function(){
        check_registro_num_proceso();
    });
    
    function check_registro_num_proceso()
    {
        var valor = parseInt($("#registro_num_proceso").val() || 0);
        
        valor = valor.toString();
        
        if(!( /[^0-9]/.test( valor ) || valor.length != <?php echo (int)$this->lang->line('registro_num_proceso_cantidad'); ?>))
        {
            $('#registro_num_proceso_button').prop('disabled', false);
            
            $('#registro_num_proceso_label_error').hide();
            $('#registro_num_proceso_label_ok').show();
        }
        else
        {
            $('#registro_num_proceso_button').prop('disabled', true);
            
            $('#registro_num_proceso_label_ok').hide();
            $('#registro_num_proceso_label_error').show();
        }
    }
    
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
            case 'nuevo_norm':

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
        
        <div class='col-sm-12'style="text-align: center;"> <label style='margin-left: 0px;' class='label-campo texto-centrado panel-heading' for=''><i class="fa fa-user-circle" aria-hidden="true"></i> Nuevo Caso <?php echo $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app_singular'); ?> </label></div>

        <div style="clear: both"></div>
        

        <div class="panel panel-default informacion_general">
            <div class="panel-heading">DATOS PERSONALES</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'>
                    <div class='form-group'>
                        
                        <label class='label-campo' for='registro_num_proceso'> <?php echo $this->lang->line('registro_num_proceso') . ': ' . $this->lang->line('registro_num_proceso_label'); ?></label><?php echo $arrCajasHTML['registro_num_proceso']; ?>
                    </div>
                </div>
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='norm_primer_nombre'><?php echo $this->lang->line('norm_primer_nombre'); ?>:</label><?php echo $arrCajasHTML['norm_primer_nombre']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='norm_segundo_nombre'><?php echo $this->lang->line('norm_segundo_nombre'); ?>:</label><?php echo $arrCajasHTML['norm_segundo_nombre']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='norm_primer_apellido'><?php echo $this->lang->line('norm_primer_apellido'); ?>:</label><?php echo $arrCajasHTML['norm_primer_apellido']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='norm_segundo_apellido'><?php echo $this->lang->line('norm_segundo_apellido'); ?>:</label><?php echo $arrCajasHTML['norm_segundo_apellido']; ?></div></div>
                
            </div>
        </div>
        
        <div style="clear: both"></div>
        
        <br /><br />
        
        <div class="row">
            <div class="col" style="text-align: center;">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 300px !important; font-size: 13px; height: 35px;" onclick="PreguntaAccion('Se procederá a Registrar un nuevo caso para el <?php echo $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app_singular'); ?> con los datos proporcionados. <br /><br /> ¿Seguro que quiere continuar?', 'nuevo_norm')"><i class="fa fa-address-book-o" aria-hidden="true"></i> Registrar Nuevo Caso </button>
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