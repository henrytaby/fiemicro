<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", "../Nuevo/Guardar",
            "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS");
    
<?php 

    echo $strValidacionJqValidate;
    
?>
    $(document).ready(function() {
        $("select").togglebutton();
        $("div.modal-backdrop").remove();
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
            case 'nuevo_lead':

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
        
        <?php
            $arrCampana = $this->mfunciones_logica->ObtenerCampana(-1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCampana);

            if(isset($arrCampana[0]) && count($arrCampana[0]) > 0)
            {
                echo '

                <div class="panel panel-default">
                    <div class="panel-heading">SELECCIONE EL RUBRO</div>
                    <div class="panel-body" style="text-align: center;">
                        <label class="label-campo" for="" style="font-style: italic;"> ¡Esta acción no se puede deshacer! </label><br />' . html_select('camp_id', $arrCampana, 'camp_id', 'camp_nombre', '', '') . '
                    </div>
                </div>

                <div style="clear: both"></div>
                ';
            }
            else
            {
                echo $this->lang->line('TablaNoRegistros');
            }
        ?>
                
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading">Datos generales del solicitante</div>
            <div class="panel-body">
                
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_solicitante'><?php echo $this->lang->line('general_solicitante'); ?>:</label><?php echo $arrCajasHTML['general_solicitante']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_telefono'><?php echo $this->lang->line('general_telefono'); ?>:</label><?php echo $arrCajasHTML['general_telefono']; ?></div></div>
                <!--<div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_email'><?php echo $this->lang->line('general_email'); ?>:</label><?php echo $arrCajasHTML['general_email']; ?></div></div> -->
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_direccion'><?php echo $this->lang->line('general_direccion'); ?>:</label><?php echo $arrCajasHTML['general_direccion']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_actividad'><?php echo $this->lang->line('general_actividad'); ?>:</label><?php echo $arrCajasHTML['general_actividad']; ?></div></div>
                <div class='col-sm-6'><div class='form-group'><label class='label-campo' for='general_ci'><?php echo $this->lang->line('general_ci'); ?>:</label><?php echo $arrCajasHTML['general_ci']; ?> <br /><br /> <?php echo $arrCajasHTML['general_ci_extension']; ?></div></div>
                
                <?php
                    echo '
                
                    <div class="col-sm-6 informacion_operacion"><div class="form-group"><label class="label-campo" for="general_destino">' . $this->lang->line("general_destino") . ':</label>' . $arrCajasHTML["general_destino"] . '</div></div>

                    <div class="col-sm-6 informacion_operacion" style="text-align: center;"><div class="form-group"><label class="label-campo" for="general_interes">' . $this->lang->line("general_interes") . ':</label><br />' . $arrCajasHTML["general_interes"] . '</div></div>

                    <div class="col-sm-6 informacion_operacion"><div class="form-group"><label class="label-campo" for="">' . $this->lang->line("general_productos") . ':</label> <br />';

                    // Listado de Servicios
                    $arrActividades = $this->mfunciones_logica->ObtenerActividades(-1);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrActividades);

                    // Lista los perfiles disponibles

                    if (isset($arrActividades[0])) 
                    {
                        $i = 0;

                        foreach ($arrActividades as $key => $value) 
                        {
                            $checked = '';
                            
                            echo '<div class="divOpciones">';
                            echo '<input id="producto' . $i , '" type="checkbox" name="producto_list[]" '. $checked .' value="' . $value["act_id"] . '">';
                            echo '<label style="margin-bottom: 0px;" for="producto' . $i , '"><span></span>' . $value["act_detalle"] . '</label>';
                            echo '</div>';

                            $i++;
                        } 
                    }
                ?>
                        
            </div>
        </div>
        
        <div style="clear: both"></div>
        
        <br /><br />
        
        <div class="row">
            <div class="col" style="text-align: center;">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 300px !important; font-size: 13px; height: 35px;" onclick="PreguntaAccion('Se procederá a Registrar un Nuevo Cliente con los datos proporcionados. <br /><br /> ¿Seguro que quiere continuar?', 'nuevo_lead')"><i class="fa fa-address-book-o" aria-hidden="true"></i> Registrar Nuevo Cliente </button>
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