
    <div class="contenido_formulario container" style="margin-top: 0px;">
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <br /><br /><br /><br />
        
        <div style="text-align: center;">
            <label class='label-campo texto-centrado panel-heading color-azul' for=''><i class="fa fa-check-circle" aria-hidden="true"></i> <?php echo "Se registró el Cliente <i>$general_solicitante</i> con el Rubro <i>$nombre_rubro</i> ¡Correctamente!"; ?> </label>
            <br />
            <label class='label-campo texto-centrado panel-heading color-azul' for=''> Para gestionar el Cliente, por favor ingrese a la bandeja del Rubro para seleccionar el registro.<br /><br />También, puede: </label>
        </div>
        
        <div style="clear: both"></div>
        
        <br /><br />
        
        <div class="row">
            <div class="col" style="text-align: center;">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 300px !important; font-size: 13px; height: 35px;" onclick="Ajax_CargadoGeneralPagina('Nuevo', 'divContenidoGeneral', 'divErrorListaResultado', 'SIN_FOCUS', '&estructura_id=<?php echo $estructura_id; ?>');"><i class="fa fa-address-book-o" aria-hidden="true"></i> Registrar Nuevo Cliente </button>
            </div>
        </div>
        
    </div>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>