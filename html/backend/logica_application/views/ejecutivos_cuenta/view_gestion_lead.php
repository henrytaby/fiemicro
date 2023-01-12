<script type="text/javascript">

    function Ajax_CargarAccion_Prospecto(codigo) {
        var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Ejecutivo/Prospecto/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }

    function ConfirmarVolverLeads()
    {
        var cnfrm = confirm('Al continuar con esta opción, saldrá de la Gestión del Cliente y volverá al listado principal, debe guardar todos los datos registrados antes de continuar ¿Seguro que quiere continuar?');
        if(cnfrm != true)
        {
            return false;
        }
        else
        {
            <?php
            
            if($_SESSION['flag_bandeja_agente'] == 0)
            {
                echo 'Ajax_CargarAccion_Prospecto(' . $codigo_ejecutivo . ');';
            }
            
            if($_SESSION['flag_bandeja_agente'] == 1)
            {
                echo "Ajax_CargarOpcionMenu('Bandeja/Ejecutivo/Ver');";
            }
            
            if($_SESSION['flag_bandeja_agente'] == 5)
            {
                echo "Ajax_CargarOpcionMenu('Ejecutivo/Prospecto/ForGestion');";
            }
            
            ?>
            
        }
    }
    
</script>

<div id="divVistaMenuPantalla" align="center">

    <?php $url_armado = $this->mfunciones_generales->setEnlaceRegistro($estructura_id, $codigo_ejecutivo, $tipo_registro); ?>
    
    <div>
        <?php 
        
        $ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
        if (preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0') !== false && strpos($ua, 'rv:11.0') !== false))
        {
            echo "<div class='TamanoContenidoGeneral'> <br /><br /> <div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> <strong> Lo sentimos, pero no puede ver los formularios desde este explorador.<br />Los formularios contienen elementos HTML5 y componentes soportados por exploradores modernos, por favor ingresa con un explorador actualizado (Chrome, Firefox, Safari, Opera, etc.). </strong> </div> </div>";
        }
        else
        {
        ?>
            <iframe frameborder="0" scrolling="yes" class="lead_iframe" src="<?php echo (site_url('Registros/Principal/?' . $url_armado));?>"> </iframe>
        <?php
        }
        
        ?>
        
    </div>
    
    <span class="BotonBarra" onclick="ConfirmarVolverLeads()">
        <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i> Volver Listado de Clientes
    </span>
    
</div>