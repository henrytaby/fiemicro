
<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('EjecutivoTitulo_zona'); ?></div>
        <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('EjecutivoSubitulo_zona'); ?></div>
        
        <div style="clear: both"></div>
        
        <br /><br />
        
        <?php
        
        if($zona_registrada == 'No')
        {
            echo "<div class='mensaje_advertencia'> " . $this->lang->line('ejecutivo_sin_zona') . " </div> <br />";            
        }
        
        ?>
        
        <?php // Lógica para actualizar el mapa y ver la ubicación actual ?>        
        <script>            
            function refresh_iframe(id) {
                document.getElementById(id).src = document.getElementById(id).src;
            }            
        </script>
        
        <span class="BotonSimpleGrande" onclick="document.getElementById('mapa_visor').contentWindow.actual()">
            <?php echo $this->lang->line('ejecutivo_ubicacion_actual'); ?>
        </span>
        
        <iframe id="mapa_visor" frameborder="0" scrolling="no" class="mapa_iframe" src="<?php echo (site_url('Ejecutivo/Zona/Mapa') . '?estructura_id=' . $estructura_id);?>">>    </iframe>
        
        <div id="divErrorBusqueda" class="mensajeBD"> </div>
                
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Ejecutivo/Ver/<?php echo $_SESSION["identificador_tipo_perfil_app"]; ?>');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>

    </div>
</div>