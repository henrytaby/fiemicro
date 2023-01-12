<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <title>
            :: <?php echo $this->lang->line('NombreSistema_corto'); ?> ::
        </title>
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
        <link rel="stylesheet" type="text/css"
              href="../../html_public/css/estilo_general_registros.css?ver=6" />
        
        <link rel="stylesheet" href="../../html_public/css/bootstrap.min.css" />
        
        <link rel="stylesheet" type="text/css"
              href="../../html_public/css/tooltip.css" />
        <link rel="stylesheet" type="text/css"
              href="../../html_public/css/font-awesome.css" />
			  
        <link rel="stylesheet" type="text/css"
        <link rel="stylesheet" type="text/css"
              href="../../html_public/js/lib/themes/base/jquery.ui.all.css" />
        <script type="text/javascript"
        src="../../html_public/js/lib/jquery-1.10.2.min.js"></script>
        
        <script type="text/javascript"
        src="../../html_public/js/lib/bootstrap.min.js"></script>
        
        <script type="text/javascript"
        src="../../html_public/js/lib/jquery.validate.min.js"></script>
        <script type="text/javascript"
        src="../../html_public/js/lib/ui/jquery-ui.custom.min.js"></script>
        <script type="text/javascript"
        src="../../html_public/js/lib/ui/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript"
        src="../../html_public/js/lib/ui/jquery.ui.datepicker-es.js"></script>
        
        <script type="text/javascript"
        src="../../html_public/js/js_pagina_logica.js"></script>

        <script type="text/javascript"
                src="../../html_public/js/lib/datatable/js/jquery.dataTables.min.js"></script>

        <script type="text/javascript"
                src="../../html_public/js/lib/datatable/js/jquery.dataTables.rowsGroup.js"></script>

        <script type="text/javascript"
                src="../../html_public/js/lib/charts/chart.bundle.js"></script>

        <script type="text/javascript"
        src="../../html_public/js/lib/transformaciones/numero_a_palabras.js"></script>

        <link rel="stylesheet" type="text/css"
              href="../../html_public/js/lib/datatable/css/jquery.dataTables.css" />

        <script type="text/javascript" src="../../html_public/js/lib/autocomplete/jquery-ui.js"></script>
        <script type="text/javascript" src="../../html_public/js/lib/autocomplete/autocomplete.js"></script>
        <style type="text/css" src="../../html_public/js/lib/autocomplete/jquery-ui.js"></style>
        <link rel="stylesheet" href="../../html_public/js/lib/autocomplete/jquery-ui.css"></link>
        
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui, shrink-to-fit=no">
        <script type="text/javascript"
        src="../../html_public/js/lib/ui/jquery.ui.timepicker.min.js"></script>
        <script type="text/javascript"
        src="../../html_public/js/lib/ui/jquery.ui.timepicker-es.js"></script>
        
            
        <script type="text/javascript"
        src="../../html_public/js/lib/select-togglebutton.js"></script>
            
        <script type="text/javascript">
            $(document).ajaxStart(function() {
                $("#PaginaPrincipal_Cargando").fadeIn("fast");
            });
            $(document).ajaxStop(function() {
                $("#PaginaPrincipal_Cargando").fadeOut("fast");
            });
            
            $.validator.addMethod(
                    "miregex",
                    function(value, element, regexp) {
                        var re = new RegExp(regexp);
                        return this.optional(element) || re.test(value);
                    },
                    "Existen caracteres no validos."
                    );
            $.validator.addMethod("noesigual", function(value, element, arg) {
                return arg != value;
            }, "Debe escoger un valor");
            $.datepicker.setDefaults($.datepicker.regional[ "es" ]);

        </script>
                
    </head>

<body class="PaginaContenidoGeneralExterno" style="left: 0;top:0;position:absolute;right:0;">

    <div id="divFondoElementoFlotante" style="display:none" class="ModalFondoSemiTransparente"></div>
    <div id="divContenidoElementoFlotante" style="display:none;" class="ElementoFlotanteExterno">            
        <span id="btnModalCerrarVentana" style="display: block; position: absolute; right: 5px; top: 10px"> 
            <a onclick="Elementos_General_MostrarElementoFlotante(false);" style="margin-left:0mm;">  
                <img src="../../html_public/imagenes/cerrar_modal.png" alt="cerrar" /></a>
        </span>
        <div id="divElementoFlotante" style="width:90%;" class="Centrado"></div>                
    </div>                           
    <div id="divCargarRespuestaScript" style="display:none" ></div>
    
    <div id="divContenidoGeneral" style="display: block; padding-bottom: 200px;"> </div>
    
    
<div style="display: none; color: #006699; top: 150px;" id="PaginaPrincipal_Cargando">
   Cargando... espere por favor...<br/>
    <img src="../../html_public/imagenes/Cargando.gif" alt="Cargando" />
</div>

</body>
</html>

<script type="text/javascript">
    
    <?php
    
    switch ($codigo_vista) {
        case 'lead_registros':
            
            echo 'Ajax_CargadoGeneralPagina("Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");';

            break;

        case 'lead_nuevo':
            
            echo 'Ajax_CargadoGeneralPagina("Nuevo", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");';

            break;
        
        case 'nuevo_onboarding':
            
            echo 'Ajax_CargadoGeneralPagina("Onboarding", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");';

            break;
        
        case 'sol_registros':
            
            echo 'Ajax_CargadoGeneralPagina("Sol_Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");';

            break;
        
        case 'nuevo_solcredito':
            
            echo 'Ajax_CargadoGeneralPagina("Sol_Nuevo", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");';

            break;
        
        case 'reporte_funnel':
            
            echo 'Ajax_CargadoGeneralPagina("ReporteFunnel", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");';

            break;
        
        // Normalizador/Cobrador
        
        case 'norm_registros':
            
            echo 'Ajax_CargadoGeneralPagina("Norm_Inicio", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");';

            break;
        
        case 'nuevo_norm':
            
            echo 'Ajax_CargadoGeneralPagina("Norm_Nuevo", "divContenidoGeneral", "divErrorListaResultado", "SIN_FOCUS", "&estructura_id=' . $estructura_id . '");';

            break;
        
        default:
            break;
    }
    ?>
        
</script>