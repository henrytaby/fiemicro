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
    function Ajax_CargarAccion_DetalleSolicitudCred(codigo, vista) {
        var strParametros = "&codigo=" + codigo + "&vista=" + vista;
        Ajax_CargadoGeneralPagina('SolWeb/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleProspecto(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Prospecto/Detalle', 'divElementoFlotante', "divErrorBusqueda", 'SLASH', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleUsuario(tipo_codigo, codigo_usuario) {
        var strParametros = "&tipo_codigo=" + tipo_codigo + "&codigo_usuario=" + codigo_usuario;
        Ajax_CargadoGeneralPagina('Usuario/Detalle', 'divElementoFlotante', "divErrorBusqueda", 'SLASH', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleCampana(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Campana/Detalle', 'divElementoFlotante', "divErrorBusqueda", 'SLASH', strParametros);
    }
    
    <?php
    // Sólo muestra la opción si tiene el Perfil
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
    {
        
        echo '
                function Ajax_CargarAccion_DocumentoProspecto(codigo, codigo_tipo_persona) {
                    var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
                    Ajax_CargadoGeneralPagina("Prospecto/Documento/Ver", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
        
        echo '
                function Ajax_CargarAccion_DocumentoProspectoReporte(codigo, codigo_tipo_persona) {
                    var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
                    Ajax_CargadoGeneralPagina("Prospecto/Documento/Unico", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
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
                    Ajax_CargadoGeneralPagina("Prospecto/Historial", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
    }
    ?>
    
    <?php
    
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
    
    function Ajax_CargarAccion_JDA_Eval(codigo, codigo_tipo_persona) {
        var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
        Ajax_CargadoGeneralPagina('Bandeja/JDAEval/Form', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_DesembCOBIS(codigo, codigo_tipo_persona) {
        var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
        Ajax_CargadoGeneralPagina('Bandeja/DesembCOBIS/Form', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function MostrarTablaResumen() {
        
        $("#resumen_bandeja").slideToggle();
    }
    
</script>

<?php $cantidad_columnas = 12;?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('VerificacionTitulo') . $region_nombres; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('VerificacionSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
            <div style="text-align: right !important; margin-left: 8%;">

                <?php
            
                    $direccion_bandeja = 'Menu/Principal';

                    if(isset($_SESSION['direccion_bandeja_actual']))
                    {
                        $direccion_bandeja = $_SESSION['direccion_bandeja_actual'];
                    }

                ?>
                
                <span class="EnlaceSimple" onclick="Ajax_CargarOpcionMenu('<?php echo $direccion_bandeja; ?>');" style="padding-right: 20px;">
                    <strong> <i class="fa fa-refresh" aria-hidden="true"></i> Actualizar Bandeja </strong>
                </span>
                
            </div>
        
        <div id="divErrorBusqueda" class="mensajeBD">  </div>
        
            <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 100%;">
                <thead>

                    <tr class="FilaCabecera">
                        
                        <!-- Similar al EXCEL -->

                        <th style="width:5%;">
                           <?php echo $this->lang->line('prospecto_codigo'); ?> </th>
                        <th style="width:5%;">
                           <?php echo $this->lang->line('import_campana'); ?> </th>
                        <th style="width:10%;">
                           <?php echo $this->lang->line('import_agente'); ?> </th>
                        <th style="width:10%;">
                           <?php echo $this->lang->line('general_solicitante_corto'); ?> </th>
                        <th style="width:10%;">
                           <?php echo $this->lang->line('general_actividad'); ?> </th>
                        <th style="width:13%;">
                           <?php echo $this->lang->line('general_destino'); ?> </th>
                        <th style="width:10%;">
                           <?php echo $this->lang->line('general_interes'); ?> </th>
                        <th style="width:10%;">
                           <?php echo $this->lang->line('registro_num_proceso'); ?> </th>
                        
                        <th style="width:5%;">
                           <?php echo $this->lang->line('prospecto_evaluacion'); ?> </th>
                        
                        <th style="width:2%;"> </th>
                        
                        <th style="width:5%;">
                           <?php echo $this->lang->line('prospecto_jda_eval'); ?> </th>
                        
                        <!-- Similar al EXCEL -->

                        <th style="width:15%;"> <?php echo $this->lang->line('TablaOpciones'); ?> </th>

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

                                <!-- Similar al EXCEL -->

                                <td style="text-align: center;">
                                    
                                    <?php
                                    if($value["camp_id"] >= 7 && $value["camp_id"] <= 12)
                                    {
                                    ?>
                                    
                                        <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleSolicitudCred('<?php echo $value["prospecto_id"]; ?>', 0)">
                                            <?php echo 'SOL_' . $value["prospecto_id"]; ?>
                                        </span>
                                    
                                    <?php
                                    }
                                    else
                                    {
                                    ?>
                                        <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleProspecto('<?php echo $value["prospecto_id"]; ?>')">
                                            <?php echo PREFIJO_PROSPECTO . $value["prospecto_id"]; ?>
                                        </span>
                                    <?php
                                    }
                                    ?>
                                    
                                </td>
                                
                                <td style="text-align: center;">
                                    <?php 
                                    
                                        echo $value["camp_nombre"];
                                        
                                        if($value["prospecto_fecha_conclusion"] == null || $value["prospecto_fecha_conclusion"] == '')
                                        {
                                            echo "<br /><i>¡Registro Pendiente!</i>";
                                        }
                                    
                                    ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    
                                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleUsuario(-1, '<?php echo $value["usuario_id"]; ?>')">
                                        <?php echo $value["ejecutivo_nombre"]; ?>
                                    </span>
                                </td>
                                
                                <td style="text-align: center;">
                                    <?php 
                                    echo $value["general_solicitante"];
                                    echo "<br />C.I.:";
                                    echo $value["general_ci"];
                                    ?>
                                </td>
                                
                                <td style="text-align: left;">
                                    <?php echo $value["general_actividad"]; ?>
                                </td>
                                
                                <td style="text-align: left;">
                                    <?php echo $value["general_destino"]; ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($value["general_interes"], 'grado_interes'); ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    
                                    <?php echo ((int)$value["registro_num_proceso"] <=0 ? $this->lang->line('prospecto_no_evaluacion') : $value["registro_num_proceso"]); ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    <?php 
                                        echo $this->mfunciones_generales->GetValorCatalogo($value["prospecto_evaluacion"], 'prospecto_evaluacion');
                                        
                                        echo ($value["registro_rechazado_texto"] == '' ? '' : '<br /> <span style="font-size: 0.8em; font-style: italic;">' . $value["registro_rechazado_texto"] . '</span>')
                                        
                                    ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    
                                    <?php
                                    
                                    if($value["camp_id"] >= 7 && $value["camp_id"] <= 12)
                                    {
                                        echo '<span style="cursor: pointer; color: #006699; font-size: 14px;" data-balloon-length="medium" data-balloon="Este registro es una \'Solicitud de Crédito\' y no fue convertido a \'Estudio de Crédito\'. Se considera dentro de este listado de acuerdo a lo establecido por el área de negocios." data-balloon-pos="right"> <i class="fa fa-info-circle" aria-hidden="true"></i> </span>';
                                    }
                                    
                                    ?>
                                    
                                </td>
                                
                                <td style="text-align: center;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($value["prospecto_jda_eval"], 'prospecto_evaluacion'); ?>
                                </td>
                                
                                <!-- Similar al EXCEL -->

                                <td style="text-align: center;">

                                    <?php
                                    // Sólo muestra la opción si tiene el Perfil
                                    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
                                    {   
                                    ?>
                                        <span style="width: 100%; padding-top: 3px;" class="BotonSimple" onclick="<?php echo ($value["camp_id"] >= 7 && $value["camp_id"] <= 12 ? 'Ajax_CargarAccion_DocumentoProspectoReporte(' . $value["prospecto_id"] . ', 6)' : 'Ajax_CargarAccion_DocumentoProspecto(' . $value["prospecto_id"] . ')'); ?>">
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

                                        <br />
                                        <span style="width: 100%; padding-top: 3px;" class="BotonSimple" onclick="Ajax_CargarAccion_Historial('<?php echo $value["prospecto_id"]; ?>', <?php echo ($value["camp_id"] >= 7 && $value["camp_id"] <= 12 ? 6 : $value["camp_id"]); ?>)">
                                            <?php echo $this->lang->line('TablaOpciones_ver_historial'); ?>
                                        </span>

                                    <?php
                                    }
                                    ?>

                                    <?php    
                                    //if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_EMPRESA))
                                    if(1==1)
                                    {
                                        if($value["camp_id"] < 7)
                                        {
                                    ?>
                                            <span style="width: 100%; padding-top: 3px;" class="BotonSimple" onclick="Ajax_CargarAccion_InformeFinal('<?php echo $value["prospecto_id"]; ?>')">
                                                <?php echo $this->lang->line('TablaOpciones_ver_informe'); ?>
                                            </span>
                                    <?php
                                        }
                                    }
                                    ?>

                                    <?php    
                                    if($value["prospecto_etapa"] == 5 || $value["prospecto_etapa"] == 22)
                                    {
                                    ?>
                                        <span style="width: 100%; padding-top: 3px;" class="BotonSimple" onclick="Ajax_CargarAccion_JDA_Eval('<?php echo $value["prospecto_id"]; ?>', '<?php echo ($value["camp_id"] >= 7 && $value["camp_id"] <= 12 ? 6 : $value["camp_id"]); ?>')">
                                            Evaluación <br /> JDA
                                        </span>
                                    <?php
                                    }
                                    ?>
                                        
                                    <?php    
                                    if($value["prospecto_etapa"] == 22)
                                    {
                                    ?>
                                        <span style="width: 100%; padding-top: 3px;" class="BotonSimple" onclick="Ajax_CargarAccion_DesembCOBIS('<?php echo $value["prospecto_id"]; ?>', '<?php echo ($value["camp_id"] >= 7 && $value["camp_id"] <= 12 ? 6 : $value["camp_id"]); ?>')">
                                            Desembolso <br /> COBIS
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
    </div>
</div>