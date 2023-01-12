
<div id="divVistaMenuPantalla" align="center">   
    
    <script type="text/javascript">
    
        function Ajax_CargarAccion_RPAGenerateCSV() {
            window.open('RPA/ExportarCSV');return false;
        }
    
        function Ajax_CargarAccion_CancelRPA(codigo_initium, texto_razon) {
            var strParametros = "&estructura_id=" + codigo_initium + "&solicitud_observacion=" + texto_razon;
            Ajax_CargadoGeneralPagina('Afiliador/Rechazar/Guardar', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }
    
        function Ajax_CargarAccion_CompleteRPA(codigo) {
            var strParametros = "&codigo=" + codigo + "&estado=1" + "&tipo_accion=1";
            Ajax_CargadoGeneralPagina('Afiliador/Cobis/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", 'SIN_FOCUS', strParametros);
        }
    
        function CambiarVista(vista) {
            var strParametros = "&vista=" + vista;
            Ajax_CargadoGeneralPagina('Menu/Cambiar', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }
        
        function Ajax_CargarAccion_ProspectoAux(codigo) {
        var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Ejecutivo/Prospecto/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    </script>
        
    <div id="divCargarFormulario" class="TamanoContenidoGeneral">
        
        <?php
        
        if(isset($_SESSION['auxiliar_bandera_upload']) && $_SESSION['auxiliar_bandera_upload'] > 0)
        {
            $texto_auxilar = '';
            
            if($_SESSION['auxiliar_bandera_upload'] == 1)
            {
                $texto_auxilar = $this->lang->line('Correcto');
            }
            
            if($_SESSION['auxiliar_bandera_upload'] == 2)
            {
                $texto_auxilar = $this->lang->line('FormularioNoFile');
            }
            
            if($_SESSION['auxiliar_bandera_upload_url'] == 'aux_lead')
            {
                if(isset($_SESSION['flag_bandeja_agente']) && $_SESSION['flag_bandeja_agente'] == 0)
                {
                    $funcion_click = $_SESSION['auxiliar_bandera_upload_url_aux'];
                }
                else
                {
                    $funcion_click = "Ajax_CargarOpcionMenu('Bandeja/Ejecutivo/Ver');";
                }
            }
            else
            {
                $funcion_click = "Ajax_CargarOpcionMenu('" . $_SESSION['auxiliar_bandera_upload_url'] . "');";
            }
            
        ?>
            <br /><br />

                <span class="PreguntaConfirmar">
                    <?php echo $texto_auxilar; ?>
                    <br /><br />
                </span>

            
            
            <div class="Centrado" style="width: 70%; text-align: center; padding-top: 10px;">
                <a class="BotonMinimalista" style="" onclick="<?php echo $funcion_click; ?>">
                    <span><?php echo $this->lang->line('BotonAceptar'); ?></span>                            
                </a>
            </div>
        
        <?php
        
        $_SESSION['auxiliar_bandera_upload'] = 0;
        
        }
        else
        {
            if(isset($_SESSION["session_informacion"]))
            {
        ?>
        
                <div class="AnuncioTitulo">
                    <i class="fa fa-home" aria-hidden="true"></i> Hola <?php echo $_SESSION["session_informacion"]["nombre"]; ?>
                </div>

                <div class="AnuncioTexto">
                    Bienvenido(a) al <?php echo $this->lang->line('NombreSistema'); ?> 
                    la herramienta tecnológica que te permite la adecuada y fácil gestión de procesos de afiliación propios de tu empresa,
                    la verificación de la documentación y la aprobación del proceso para un seguimiento oportuno y detección de cuellos de botella.

                </div>

                <div class="AnuncioTitulo">
                    <i class="fa fa-shield" aria-hidden="true"></i> Nos importa tu Seguridad
                </div>

                <div class="AnuncioTexto">

                    Es altamente recomendable que no compartas tus credenciales de acceso y que renueves tu contraseña periodicamente. Todas las acciones realizadas
                    serán registradas en los Logs del Sistema.

                    <br /><br />

                    <p>
                        Tu último acceso fue el <?php echo $_SESSION["session_informacion"]["fecha_ultimo_acceso"]; ?>.
                    </p>

                    <br /><br />

                    <?php if($_SESSION["session_informacion"]["ad_active"] == 1){ ?>
                        <strong><u> <?php echo $this -> lang -> line("ad_activo") ?></u> </strong>

                    <?php
                    }elseif($_SESSION["session_informacion"]["codigo"] != 1 && $_SESSION["session_informacion"]["login"] != 'rpa.onboarding')
                    {
                    ?>
                        <strong>Tu contraseña actual deberá ser renovada en <u> <?php echo $_SESSION["session_informacion"]["dias_cambio_password"]; ?> día(s). </u> </strong>
                    <?php
                    }
                    ?>
                        
                </div>
        
        <?php
            }
        }

        ?>
    
    </div>