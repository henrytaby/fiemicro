<script type="text/javascript">
    
    function Ajax_CargarAccion_EditarEtapa(codigo, codigo_flujo) {
        
        <?php
        if($editar == 1)
        {
        ?>
            var strParametros = "&codigo_etapa=" + codigo + "&codigo_flujo=" + codigo_flujo;
            Ajax_CargadoGeneralPagina('Flujo/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        <?php
        }
        ?>
    }
    
</script>

    <div align="center" style="width: 600px; background-color: #eeeeee; padding: 20px 50px; border-radius: 30px; border: 2px solid #f5811e;">

    <?php

        if(isset($tree[0]) && count($tree[0])>0)
        {
            echo '<div class="tree">';
            echo '<ul> <li> <img src="html_public/imagenes/start.png" style="width: 80px;" />';
            echo $this->mfunciones_generales->menu($tree);

            echo '</li></ul>';

            echo '</div>';
            
            echo '  <div style="clear: both"></div>
                    <br /><br />
                    <img src="html_public/imagenes/stop.png" style="width: 80px;" />';
        }
        else
        {
            echo $this->lang->line('TablaNoRegistros'); 
        }

    ?>

    </div>