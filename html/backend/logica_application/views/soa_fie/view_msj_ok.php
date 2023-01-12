
<div id="divDatosPersonales">    
        
    <div class="PreguntaConfirmacion TamanoContenidoGeneral">
        
        <br /><br />
        
        <div id="divImgCondicion2" style="border: 0px !IMPORTANT;">

            <span class="PreguntaConfirmar">
                <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> La Acción solicitada se efectuó correctamente.<br /><br />Se borraron <?php echo $filas_afectadas; ?> registros incluidos también los consumos de "token" y "refresh_token".
                <br /><br />
            </span>

        </div>

        <div class="Centrado" style="width: 70%; text-align: center; padding-top: 10px;">
            <a class="BotonMinimalista" style="" onclick="Ajax_CargarOpcionMenu('<?php echo $ruta_redireccion; ?>');">
                <span> CONTINUAR </span>                            
            </a>
        </div>
    </div>
</div>