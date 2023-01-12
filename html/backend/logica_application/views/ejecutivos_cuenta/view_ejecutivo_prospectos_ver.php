<script type="text/javascript">
<?php
// Si no existe informaci?n, no se mostrar? como tabla con columnas con ?rden
if(count($arrRespuesta[0]) > 0)
{
?>  
    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": <?php echo PAGINADO_TABLA; ?>,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[0, "asc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
<?php
}
?>
    <?php
    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
    {
        echo '
                function Ajax_CargarAccion_RegistroReturn(codigo, codigo_tipo_persona) {
                    var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
                    Ajax_CargadoGeneralPagina("Registro/Documento/Ver", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
    }
    
    // Sólo muestra la opción si tiene el Perfil
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
    {
        echo '
                function Ajax_CargarAccion_AdministrarProspecto(codigo, codigo_tipo_persona) {
                    var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
                    Ajax_CargadoGeneralPagina("Registro/Documento/Ver", "divElementoFlotante", "divErrorBusqueda", "", strParametros);
                }
            ';
    }
    ?>

    <?php
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL))
    {
        
        echo '
                function Ajax_CargarAccion_Historial(codigo, codigo_tipo_persona) {
                    var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
                    Ajax_CargadoGeneralPagina("Prospecto/Historial", "divElementoFlotante", "divErrorBusqueda", "", strParametros);
                }
            ';
    }
    
    //if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_EMPRESA))
    if(1==1)
    {        
        echo '
                function Ajax_CargarAccion_InformeFinal(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Prospecto/Documento/Informe", "divElementoFlotante", "divErrorBusqueda", "", strParametros);
                }
            ';
    }
    ?>
    
    function Ajax_CargarAccion_Consolidar(codigo) {
        
        var cnfrm = confirm('<?php echo $this->lang->line('FormularioForzarConsolidado'); ?>');
        if(cnfrm != true)
        {
            return false;
        }
        else
        {
            var strParametros = "&codigo=" + codigo;
            Ajax_CargadoGeneralPagina('Registro/Prospecto/Consolidar', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }
    }
    
    function Ajax_CargarAccion_ConsolidarSol(codigo) {
        
        var cnfrm = confirm('<?php echo $this->lang->line('FormularioForzarConsolidado'); ?>');
        if(cnfrm != true)
        {
            return false;
        }
        else
        {
            var strParametros = "&codigo=" + codigo;
            Ajax_CargadoGeneralPagina('Registro/Prospecto/ConsolidarSol', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }
    }
    
    function Ajax_CargarAccion_ConsolidarNorm(codigo) {
        
        var cnfrm = confirm('<?php echo $this->lang->line('FormularioForzarConsolidado'); ?>');
        if(cnfrm != true)
        {
            return false;
        }
        else
        {
            var strParametros = "&codigo=" + codigo;
            Ajax_CargadoGeneralPagina('Registro/Prospecto/ConsolidarNorm', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }
    }
    
    function Ajax_CargarAccion_DetalleNorm(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Norm/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleSolicitudCred(codigo, vista) {
        var strParametros = "&codigo=" + codigo + "&vista=" + vista;
        Ajax_CargadoGeneralPagina('SolWeb/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleProspecto(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Prospecto/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_GestionLead(codigo, codigo_ejecutivo, tipo_registro) {
        var strParametros = "&codigo=" + codigo + "&codigo_ejecutivo=" + codigo_ejecutivo + "&tipo_registro=" + tipo_registro;
        Ajax_CargadoGeneralPagina('Ejecutivo/Prospecto/Gestion', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_TransfProspecto(codigo, visita, tipo_registro) {
        var strParametros = "&codigo=" + codigo + "&visita=" + visita + "&tipo_registro=" + tipo_registro;
        Ajax_CargadoGeneralPagina('Ejecutivo/TransfPros/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_Prospecto(codigo) {
        var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Ejecutivo/Prospecto/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_EstadoEvolucion(codigo) {
        var strParametros = "&estructura_id=" + codigo;
        Ajax_CargadoGeneralPagina('Ejecutivo/ReporteFunnel', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_EstadoEvolucionNorm(codigo) {
        var strParametros = "&estructura_id=" + codigo;
        Ajax_CargadoGeneralPagina('Ejecutivo/ReporteEvolucionNorm', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_NormCheckIn(codigo) {
        
        var cnfrm = confirm('<?php echo $this->lang->line('norm_mje_forzarcheckin'); ?>');
        if(cnfrm != true)
        {
            return false;
        }
        else
        {
            var strParametros = "&codigo=" + codigo;
            Ajax_CargadoGeneralPagina('Registro/Prospecto/NormCheckIn', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }
    }
    
</script>

<?php $cantidad_columnas = 9;?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br />
        
        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
        
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('TablaOpciones_prospectos_asignados_titulo') . $arrEjectutivo[0]['perfil_app_nombre'] . '<br /> <i class="fa fa-inbox" aria-hidden="true"></i> ' . (isset($arrEjectutivo[0]['usuario_nombre'])?$arrEjectutivo[0]['usuario_nombre']:'') . $texto_noejecutivo; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('TablaOpciones_prospectos_asignados_subtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <?php
        
        if($estructura_id > 0)
        {
            // Oficial de Negocios
            if($arrEjectutivo[0]['perfil_app_id'] == 1)
            {
        ?>
                <div align="left" class="BotonesVariasOpciones">

                    <span class="BotonMinimalista" onclick="Ajax_CargarAccion_GestionLead('-1', '<?php echo $arrEjectutivo[0]['ejecutivo_id']; ?>', 'nuevo_onboarding');">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Nuevo Onboarding
                    </span>

                </div>

                <div align="left" class="BotonesVariasOpciones">

                    <span class="BotonMinimalista" onclick="Ajax_CargarAccion_GestionLead('-1', '<?php echo $arrEjectutivo[0]['ejecutivo_id']; ?>', 'nuevo_solcredito');">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Nueva Sol. Crédito
                    </span>

                </div>

                <div align="left" class="BotonesVariasOpciones">

                    <span class="BotonMinimalista" onclick="Ajax_CargarAccion_EstadoEvolucion('<?php echo $arrEjectutivo[0]['ejecutivo_id']; ?>', 'nuevo_solcredito');">
                        <i class="fa fa-table" aria-hidden="true"></i> Estado de Evolución
                    </span>

                </div>

            <?php
            }
            
            // Normalizador/Cobrador
            if($arrEjectutivo[0]['perfil_app_id'] == 3)
            {
            ?>
                <div align="left" class="BotonesVariasOpciones">

                    <span class="BotonMinimalista" onclick="Ajax_CargarAccion_GestionLead('-1', '<?php echo $arrEjectutivo[0]['ejecutivo_id']; ?>', 'nuevo_norm');">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Nuevo Caso
                    </span>

                </div>
        
                <div align="left" class="BotonesVariasOpciones">

                    <span class="BotonMinimalista" onclick="Ajax_CargarAccion_EstadoEvolucionNorm('<?php echo $arrEjectutivo[0]['ejecutivo_id']; ?>', 'nuevo_norm');">
                        <i class="fa fa-table" aria-hidden="true"></i> Estado de Evolución
                    </span>

                </div>
        <?php
            }
            
            echo '<div style="clear: both"></div>';
        }
        ?>
            <div style="text-align: right !important; margin-left: 8%;">

                <?php
            
                    $direccion_bandeja = 'Menu/Principal';

                    if(isset($_SESSION['direccion_bandeja_actual']))
                    {
                        $direccion_bandeja = $_SESSION['direccion_bandeja_actual'];
                    }

                ?>
                
                <span class="EnlaceSimple" onclick="<?php echo $direccion_bandeja; ?>" style="padding-right: 20px;">
                    <strong> <i class="fa fa-refresh" aria-hidden="true"></i> Actualizar Bandeja </strong>
                </span>
                
            </div>
        
        <div id="divErrorBusqueda" class="mensajeBD">  </div>
        
        <?php        
        if (count($arrRespuesta[0]) > 0)
        {
        ?>
        
            <div style="text-align: left !important; margin-left: 8%;">

                <span class="EnlaceSimple" onclick="MostrarTablaResumen();">
                    <strong><?php echo $this->lang->line('TablaOpciones_mostrar_resumen'); ?> </strong>
                </span>
                
                <div id="resumen_bandeja" class="ResumenBandeja">

                    <table class="tablaresultados Mayuscula" border="0">

                        <tr class="FilaGris">

                            <td colspan="3" style="width: 33%; font-weight: bold; text-align: center;">
                               <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo 'Tiempo asignado a la etapa: ' . $tiempo_etapa_asignado . ' hora(s)'; ?>
                            </td>
                            
                        </tr>
                        
                        <tr class="FilaGris">

                            <td style="width: 33%; font-weight: bold; text-align: center;">
                                <?php echo $this->mfunciones_generales->TiempoEtapaColor(100); ?>
                                <?php echo $arrResumen[0]['contador_100']; ?> A tiempo
                            </td>

                            <td style="width: 33%; font-weight: bold; text-align: center;">
                                <?php echo $this->mfunciones_generales->TiempoEtapaColor(50); ?>
                                <?php echo $arrResumen[0]['contador_50']; ?> Pendiente(s)
                            </td>

                            <td style="width: 34%; font-weight: bold; text-align: center;">
                                <?php echo $this->mfunciones_generales->TiempoEtapaColor(-1); ?>
                                <?php echo $arrResumen[0]['contador_0']; ?> Atrasado(s)
                            </td>

                        </tr>

                    </table>

                </div>
                
            </div>

        <?php
        }
        ?>
		<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
			<thead>
			
                            <tr class="FilaCabecera">

                                <th style="width:5%;">
                                   N°
                                </th>

                                <!-- Similar al EXCEL -->

                                <th style="width:5%;"> <?php echo $this->lang->line('prospecto_codigo'); ?> </th>
                                <th style="width:10%;"> Tipo Registro </th>
                                <th style="width:15%;"> <?php echo ((int)$arrEjectutivo[0]['perfil_app_id']==3 ? 'Cliente/Caso' : 'Solicitante'); ?> </th>
                                <th style="width:10%;"> <?php echo ((int)$arrEjectutivo[0]['perfil_app_id']==3 ? $this->lang->line('norm_num_proceso') : $this->lang->line('general_ci')); ?> </th>
                                <th style="width:10%;"> <?php echo $this->lang->line('prospecto_fecha_asignaccion'); ?> </th>
                                <th style="width:10%;"> Horas Laborales Acumuladas </th>
                                <th style="width:2%;"> </th>

                                <!-- Similar al EXCEL -->
                                
                                <th style="width:33%;"> <?php echo $this->lang->line('TablaOpciones'); ?> </th>

                            </tr>
			</thead>
			<tbody>
				<?php
				
				$mostrar = true;
				if (count($arrRespuesta[0]) == 0) 
				{
                                    $mostrar = false;
				}

				if ($mostrar) 
				{                
                                    $i=0;
                                    $strClase = "FilaBlanca";
                                    foreach ($arrRespuesta as $key => $value) 
                                    {                    
                                        $i++;

                                        ?> 
                                        <tr class="<?php echo $strClase; ?>">

                                            <td style="text-align: center;">
                                                    <?php echo $i; ?>
                                            </td>

                                            <!-- Similar al EXCEL -->

                                            <td style="text-align: center;">
                                                
                                                <?php
                                                
                                                switch ((int)$value['camp_id']) {
                                                    case 6:

                                                        echo '<span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleSolicitudCred(\'' . $value["prospecto_id"] . '\')">';
                                                        echo 'SOL_' . $value["prospecto_id"];
                                                        echo '</span>';

                                                        break;
                                                    
                                                    case 13:

                                                        echo '<span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleNorm(\'' . $value["prospecto_id"] . '\')">';
                                                        echo $this->lang->line('norm_prefijo') . $value["prospecto_id"];
                                                        echo '</span>';

                                                        break;

                                                    default:
                                                        
                                                        if($value['onboarding'] == 0)
                                                        {
                                                        ?>
                                                            <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleProspecto('<?php echo $value["prospecto_id"]; ?>')">
                                                                <?php echo PREFIJO_PROSPECTO . $value["prospecto_id"]; ?>                                    
                                                            </span>
                                                        <?php
                                                        }
                                                        else
                                                        {
                                                            // Se consulta si es un registro Tercero para actualizarlo
                                                            $codigo_terceros = $this->mfunciones_generales->ObtenerCodigoTercerosProspecto($value["prospecto_id"]);

                                                            if($codigo_terceros != 0)
                                                            {
                                                                echo PREFIJO_TERCEROS . $codigo_terceros;
                                                            }
                                                            else
                                                            {
                                                                echo PREFIJO_PROSPECTO . $value["prospecto_id"];
                                                            }
                                                        }
                                                        
                                                        break;
                                                }
                                                
                                                ?>
                                                
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo str_replace('/', '/ ', $value["camp_nombre"]); ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["general_solicitante"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value['general_ci'] . " " . ($value['onboarding'] == 0 ? $this->mfunciones_generales->GetValorCatalogo($value['general_ci_extension'], 'extension_ci') : ($value['camp_id']==6 ? $value['general_ci_extension'] : '')); ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php 
                                                
                                                    echo $value["prospecto_fecha_asignacion"]; 
                                                
                                                    if((int)$value['camp_id'] == 13)
                                                    {
                                                        echo '<br /><span style="font-size: 0.9em; font-style: italic; ' . ($value['cv_fecha_compromiso_check'] ? 'color: #db1b1c;' : '') . '">' . $this->lang->line('cv_fecha_compromiso_abrev') . '<br />' . $value['cv_fecha_compromiso'] . '</span>';
                                                    }
                                                    
                                                ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo number_format($value["horas_laborales"], 0, ',', '.'); ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <span style="font-size: 0.1px; color: rgba(85, 85, 85, 0);"><?php echo $value["tiempo_etapa"]; ?></span>
                                                <?php echo $this->mfunciones_generales->TiempoEtapaColor($value["tiempo_etapa"]); ?>
                                            </td>

                                            <!-- Similar al EXCEL -->
                                            
                                            <td style="text-align: center;">
                                                
                                                <?php
                                                
                                                switch ((int)$value["camp_id"]) {
                                                    
                                                    // Solicitud de Crédito
                                                    case 6:

                                                        $tipo_gestion = 'solicitud_credito';

                                                        break;

                                                    // Normalizador/Cobrador
                                                    case 13:
                                                    
                                                        $tipo_gestion = 'normalizador';
                                                        
                                                        break;
                                                        
                                                    default:
                                                        
                                                        $tipo_gestion = 'unidad_familiar';
                                                        
                                                        break;
                                                }
                                                
                                                ?>
                                                
                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_GestionLead('<?php echo $value["prospecto_id"]; ?>', '<?php echo $value["ejecutivo_id"]; ?>', '<?php echo $tipo_gestion; ?>')">
                                                    <?php echo $this->lang->line('TablaOpciones_gestion_lead'); ?>
                                                </span>
                                                
                                                <?php
                                                // Sólo muestra la opción si tiene el Perfil
                                                if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
                                                {
                                                ?>
                                                    <span class="BotonSimple" onclick="Ajax_CargarAccion_AdministrarProspecto('<?php echo $value["prospecto_id"]; ?>', '<?php echo $value["camp_id"]; ?>')">
                                                        <?php echo $this->lang->line('TablaOpciones_revisar_documentacion'); ?>
                                                    </span>
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                // Sólo muestra la opción si tiene el Perfil
                                                if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL))
                                                {
                                                ?>

                                                    <span class="BotonSimple" onclick="Ajax_CargarAccion_Historial('<?php echo $value["prospecto_id"]; ?>', '<?php echo $value["camp_id"]; ?>')">
                                                        <?php echo $this->lang->line('TablaOpciones_ver_historial'); ?>
                                                    </span>

                                                <?php
                                                }
                                                ?>

                                                <?php    
                                                if((int)$value["camp_id"] == 13)
                                                {    
                                                ?>
                                                    <span class="BotonSimple" onclick="Ajax_CargarAccion_NormCheckIn('<?php echo $value["prospecto_id"]; ?>')">
                                                        <?php echo $this->lang->line('TablaOpciones_norm_forzarcheckin'); ?>
                                                    </span>
                                                <?php
                                                }
                                                ?>
                                                
                                                <?php
                                                if($value["prospecto_consolidado"] == 0)
                                                {
                                                    switch ((int)$value["camp_id"]) {

                                                        // Solicitud de Crédito
                                                        case 6:

                                                            $tipo_consolidado = 'Ajax_CargarAccion_ConsolidarSol';

                                                            break;

                                                        // Normalizador/Cobrador
                                                        case 13:

                                                            $tipo_consolidado = 'Ajax_CargarAccion_ConsolidarNorm';

                                                            break;

                                                        default:

                                                            $tipo_consolidado = 'Ajax_CargarAccion_Consolidar';

                                                            break;
                                                    }
                                                    
                                                ?>
                                                    <span class="BotonSimple" onclick="<?php echo $tipo_consolidado . "('" . $value["prospecto_id"] . "');"; ?>">
                                                        <i class="fa fa-lightbulb-o"></i> Forzar <br /> Consolidado
                                                    </span>
                                                <?php
                                                }
                                                ?>
                                                
                                                <?php
                                                if($_SESSION['flag_bandeja_agente'] == 0)
                                                {
                                                ?>
                                                    <span class="BotonSimple" onclick="Ajax_CargarAccion_TransfProspecto('<?php echo $value["ejecutivo_id"]; ?>', '<?php echo $value["prospecto_id"]; ?>', '<?php echo $value["camp_id"]; ?>')">
                                                        <?php echo $this->lang->line('TablaOpciones_prospectos_transferir'); ?>
                                                    </span>
                                                <?php
                                                }
                                                ?>
                                                
                                                <?php    
                                                //if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_EMPRESA))
                                                if($value['onboarding'] == 0)
                                                {    
                                                ?>
                                                    <span style="width: 100%; padding-top: 3px;" class="BotonSimple" onclick="Ajax_CargarAccion_InformeFinal('<?php echo $value["prospecto_id"]; ?>')">
                                                        <?php echo $this->lang->line('TablaOpciones_ver_informe'); ?>
                                                    </span>
                                                <?php
                                                }
                                                ?>
                                                
                                            </td>
                                            
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                    <?php
                                    $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca";
                                    //endfor;
				}
				else 
				{
				?>
				<tr>
                                    <td style="width: 55%; color: #25728c;" colspan="<?php echo $cantidad_columnas; ?>">
                                        <br><br>
                                        <?php echo $this->lang->line('TablaNoRegistros'); ?>
                                        <br><br>
                                    </td>                               
				</tr>
			<?php } ?>            
		</table>
		
		<div id="divErrorBusqueda" class="mensajeBD">  </div>

                <?php
                
                if($_SESSION['flag_bandeja_agente'] == 0)
                {
                ?>
                
                    <div class="Botones2Opciones">
                        <a onclick="Ajax_CargarOpcionMenu('Ejecutivo/Ver/<?php echo $_SESSION["identificador_tipo_perfil_app"]; ?>');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
                    </div>
                
                <?php
                }
                ?>
                
    </div>
</div>