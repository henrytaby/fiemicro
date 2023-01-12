<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", "../Onboarding/Guardar",
            "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS");
    
<?php 

    echo $strValidacionJqValidate;
    
?>
    $(document).ready(function() {
        $("div.modal-backdrop").remove();
        $("#tipo_cuenta").togglebutton();
    });
    
    $('#di_primernombre').attr("style","text-transform: capitalize;");
    $('#di_segundo_otrosnombres').attr("style","text-transform: capitalize;");
    $('#di_primerapellido').attr("style","text-transform: capitalize;");
    $('#di_segundoapellido').attr("style","text-transform: capitalize;");
    
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
            case 'nuevo_onboarding':

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
        
        <div class='col-sm-12'style="text-align: center;"> <label class='label-campo texto-centrado panel-heading' for=''><i class="fa fa-user-circle" aria-hidden="true"></i> Nuevo Registro Asistido</label></div>

        <div style="clear: both"></div>
        

        <div class="panel panel-default informacion_general">
            <div class="panel-heading texto-centrado"><?php echo $this->lang->line('tipo_cuenta'); ?></div>
            <div class="panel-body">
                
                <div class='col-sm-12' style="text-align: center;"><div class='form-group'><br /><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('tipo_cuenta', '', 'tipo_cuenta', '-1', '-1', '-1', 'SINSELECCIONAR'); ?></div></div>
        
            </div>
        </div>
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading">Cédula de identidad</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='cI_numeroraiz'><?php echo $this->lang->line('cI_numeroraiz'); ?>:</label><?php echo $arrCajasHTML['cI_numeroraiz']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='cI_complemento'><?php echo $this->lang->line('cI_complemento'); ?>:</label><?php echo $arrCajasHTML['cI_complemento']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='cI_lugar_emisionoextension'><?php echo $this->lang->line('cI_lugar_emisionoextension'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('cI_lugar_emisionoextension', '', 'cI_lugar_emisionoextension'); ?></div></div>
                
            </div>
        </div>
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading">DATOS DE IDENTIFICACIÓN</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='di_primernombre'><?php echo $this->lang->line('di_primernombre'); ?>:</label><?php echo $arrCajasHTML['di_primernombre']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='di_segundo_otrosnombres'><?php echo $this->lang->line('di_segundo_otrosnombres'); ?>:</label><?php echo $arrCajasHTML['di_segundo_otrosnombres']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='di_primerapellido'><?php echo $this->lang->line('di_primerapellido'); ?>:</label><?php echo $arrCajasHTML['di_primerapellido']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='di_segundoapellido'><?php echo $this->lang->line('di_segundoapellido'); ?>:</label><?php echo $arrCajasHTML['di_segundoapellido']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='direccion_email'><?php echo $this->lang->line('direccion_email'); ?>:</label><?php echo $arrCajasHTML['direccion_email']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='dir_notelefonico'><?php echo $this->lang->line('dir_notelefonico'); ?>:</label><?php echo $arrCajasHTML['dir_notelefonico']; ?></div></div>
                
            </div>
        </div>
        
        <div style="clear: both"></div>
        
        <br /><br />
        
        <div class="row">
            <div class="col" style="text-align: center;">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 300px !important; font-size: 13px; height: 35px;" onclick="PreguntaAccion('Se procederá a Registrar un Nuevo Proceso de Onboarding con los datos proporcionados. <br /><br /> ¿Seguro que quiere continuar?', 'nuevo_onboarding')"><i class="fa fa-address-book-o" aria-hidden="true"></i> Registrar Nuevo Cliente </button>
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