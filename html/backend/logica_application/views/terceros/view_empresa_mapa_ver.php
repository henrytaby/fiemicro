
<script>
    document.getElementById("divContenidoElementoFlotante").style.top = "50px";
</script>

<div style="overflow-y: auto; height: 550px;">

    <?php // Lógica para actualizar el mapa y ver la ubicación actual ?>        
    <script>            
        function refresh_iframe(id) {
            document.getElementById(id).src = document.getElementById(id).src;
        }
        
        function actualizar_direccion(id) {
            
            if (typeof document.FormularioRegistroLista != "undefined")
            {
                document.getElementById('empresa_direccion_literal').value = id;
            }
        }
        
    </script>

    <div class="FormularioSubtituloComentarioNormal "> Para Guardar la ubicación de la empresa, solamente ubique el marcador y se guardará automáticamente. Si no ve el marcador o requiere registrar la posición manulmente puede hacer 'doble-clic' sobre el mapa. </div>

    <div style="clear: both"></div>
    
    <div align="center">
        <span class="BotonSimpleGrande" style="width: 50% !important;" onclick="document.getElementById('mapa_visor').contentWindow.actual()">
            <?php echo $this->lang->line('ejecutivo_ubicacion_actual'); ?>
        </span>
    </div>
    
    <iframe id="mapa_visor" frameborder="0" scrolling="no" class="mapa_iframe" style="height: 550px !important;" src="<?php echo (site_url('Afiliador/Zona/Mapa') . '?estructura_id=' . $estructura_id);?>">>    </iframe>

    <div id="divErrorBusqueda" class="mensajeBD"> </div>

</div>