<script type="text/javascript">
    function Exportar(tipo, etapa) {
        var w = window.open('about:blank');
        w.document.write('<form method="post" id="formID" action="Dashboard/Reporte/Tabla"><span style="font-family: tahoma;">Generando Reporte... ' + (tipo=='excel' ? 'Al completar el proceso puede cerrar esta pestaña.' : '') + '</span> <input type="hidden" value="' + $("#sel_departamento_tabla").val() + '" name="sel_departamento"/> <input type="hidden" value="' + $("#sel_agencia_tabla").val() + '" name="sel_agencia"/> <input type="hidden" value="' + $("#sel_oficial_tabla").val() + '" name="sel_oficial"/> <input type="hidden" value="' + $("#campoFiltroFechaDesde_tabla").val() + '" name="campoFiltroFechaDesde"/> <input type="hidden" value="' + $("#campoFiltroFechaHasta_tabla").val() + '" name="campoFiltroFechaHasta"/> <input type="hidden" value="' + $("#campoFiltroFechaDCDesde_tabla").val() + '" name="campoFiltroFechaDCDesde"/> <input type="hidden" value="' + $("#campoFiltroFechaDCHasta_tabla").val() + '" name="campoFiltroFechaDCHasta"/> <input type="hidden" value="' + tipo + '" name="tipo"/> <input type="hidden" value="' + etapa + '" name="etapa"/>');
        w.document.getElementById("formID").submit();
        return false;
    }
    
    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": <?php echo PAGINADO_TABLA + 5; ?>,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[1, "asc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
    
    $("#tabla_resultado").show();    
    $("#tabla_resultado_opcion").hide();

    // Set variable a utilizar
    var MyApp = {};
    
    function MostrarConfirmaciónTabla(id, user, campana, ejecutivo_id)
    {
        $("#tabla_resultado").hide();
        $("#tabla_resultado_opcion").fadeIn(300);
        $("#btnGenerarTabla").addClass("ocultar_elemento");
        
        MyApp.codigo = id;
        MyApp.usuario = user;
        MyApp.campana = campana;
        MyApp.ejecutivo_id = ejecutivo_id;
        
        document.getElementById("texto_codigo").innerHTML = "Más Opciones del LEAD_"+MyApp.codigo;        
    }
    
    function OcultarConfirmaciónTabla()
    {
        $("#tabla_resultado").fadeIn(300);    
        $("#tabla_resultado_opcion").hide();
        $("#btnGenerarTabla").removeClass("ocultar_elemento");
    }
    
    <?php
    // Sólo muestra la opción si tiene el Perfil
    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_PROSPECTO))
    {        
        echo '
                function Ajax_CargarAccion_DetalleProspectoReporte(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Prospecto/Detalle", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
    }
    ?>

    <?php
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
    {        
        echo '
                function Ajax_CargarAccion_DocumentoProspectoReporte(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Prospecto/Documento/Unico", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
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
                    Ajax_CargadoGeneralPagina("Prospecto/Documento/Informe", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
    }
    ?>
        
    <?php
    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_USUARIO))
    {        
        echo '
                function Ajax_CargarAccion_DetalleUsuarioReporte(tipo_codigo, codigo_usuario) {
                    var strParametros = "&tipo_codigo=" + tipo_codigo + "&codigo_usuario=" + codigo_usuario;
                    Ajax_CargadoGeneralPagina("Usuario/Detalle", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
    }
    ?>

    <?php
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL))
    {        
        echo '
                function Ajax_CargarAccion_HistorialReporte(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Prospecto/Historial", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
    }
    ?>
        
    <?php
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_SEGUIMIENTO))
    {        
        echo '
                function Ajax_CargarAccion_SeguimientoProspectoReporte(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Prospecto/Seguimiento", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
    }
    ?>
    
    function Ajax_CargarAccion_DetalleSolCred(codigo, vista) {
        var strParametros = "&codigo=" + codigo + "&vista=" + vista;
        Ajax_CargadoGeneralPagina('SolWeb/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<div id="tabla_resultado">
    
    <table border="0" style="width: 90%">
        <tr>
            <td class="inv-piramide-titulo" style="width: 95%; text-align: center;">
                ESTADO DE EVOLUCIÓN - <?php echo mb_strtoupper($etapa_nombre); ?>
            </td>
            <td class="inv-piramide-titulo" style="width: 5%; text-align: right;">
                <span style="font-size: 1.1em;" title="Ir arriba" class="EnlaceSimple" onclick='$("html,body").animate({scrollTop: $("#FormularioOpcionesReporte").show().offset().top - 80}, 600);'>
                    <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
                </span>
            </td>
        </tr>
    </table>
    
    <div style="width: 100%; overflow-x: auto">
        
        <?php
        if (isset($arrRespuesta[0]) && (int)$arrRespuesta[0]['etapa_id'] == 24)
        {
            $col_aux = 1;
        }
        else
        {
            $col_aux = 0;
        }
        ?>
        
        <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
            <thead>
            <tr>
                <th style="width: 5%;"> Código Cliente </th>
                <th style="width: 7%;"><?php echo $this->lang->line('terceros_columna1'); ?></th>
                <th style="width: <?php echo ($col_aux==1 ? '6' : '7'); ?>%;"><?php echo $this->lang->line('general_solicitante_corto'); ?></th>
                <th style="width: <?php echo ($col_aux==1 ? '6' : '7'); ?>%;"><?php echo $this->lang->line('general_ci'); ?></th>
                <th style="width: 7%;"><?php echo $this->lang->line('import_campana'); ?></th>
                <th style="width: <?php echo ($col_aux==1 ? '6' : '7'); ?>%;"><?php echo $this->lang->line('import_agente'); ?></th>
                <th style="width: <?php echo ($col_aux==1 ? '6' : '7'); ?>%;"><?php echo $this->lang->line('prospecto_etapa_actual'); ?></th>
                <th style="width: 7%;"><?php echo $this->lang->line('general_actividad'); ?></th>
                <th style="width: 7%;"><?php echo $this->lang->line('general_destino'); ?></th>
                <th style="width: 7%;"><?php echo $this->lang->line('registro_num_proceso'); ?></th>
                <th style="width: <?php echo ($col_aux==1 ? '6' : '7'); ?>%;"><?php echo $this->lang->line('prospecto_jda_eval'); ?></th>
                
                <?php
                if($col_aux == 1)
                {
                    echo '<th style="width: 7%;"> Fecha ' . $this->lang->line('prospecto_desembolso') . '</th>';
                }
                ?>
                
                <th style="width: 7%;"><?php echo $this->lang->line('sol_monto_bs'); ?></th>
                <th style="width: <?php echo ($col_aux==1 ? '6' : '7'); ?>%;"><?php echo $this->lang->line('terceros_columna8'); ?></th>
                <th style="width: 7%;">Días Transcurridos</th>
                <th style="width: <?php echo ($col_aux==1 ? '3' : '4'); ?>%;">Opc.</th>
            </tr>
            </thead>
            <?php
            if (isset($arrRespuesta[0])) 
            {
            ?>
                <tbody>
                <?php foreach ($arrRespuesta as $key => $value):?>
                    <tr class="FilaBlanca">
                        <td align="center"><?php echo ($value["tipo"]==1 ? PREFIJO_PROSPECTO : 'SOL_') . $value["prospecto_id"]?></td>
                        <td align="center"><?php echo $value["agencia_nombre"]; ?></td>
                        <td align="center"><?php echo $value["general_solicitante"]; ?></td>
                        <td align="center"><?php echo $value["general_ci"] . ' ' . $value["general_ci_extension"]; ?></td>
                        <td align="center"><?php echo $value["camp_nombre"]?></td>
                        <td align="center"><?php echo $value["ejecutivo_nombre"]?></td>
                        <td align="center"><?php echo $value["etapa_nombre"]?></td>
                        <td align="center"><?php echo $value["general_actividad"]?></td>
                        <td align="center"><?php echo $value["general_destino"]?></td>
                        <td align="center"><?php echo $value["registro_num_proceso"]?></td>
                        <td align="center"><?php echo $this->mfunciones_generales->GetValorCatalogo($value["prospecto_jda_eval"], 'prospecto_evaluacion'); ?></td>
                        
                        <?php
                        if($col_aux == 1)
                        {
                            echo '<td align="center">' . $value["desembolso_fecha"] . '</td>';
                        }
                        ?>
                        
                        <td align="center"><?php echo $value["sol_monto_bs"]?></td>
                        <td align="center"><?php echo $value["fecha_registro"]?></td>
                        <td align="center"><?php echo $value["dias_calendario"]?></td>
                        <td align="center">

                            <?php
                            if($value["tipo"]==1)
                            {
                            ?>
                                <span class="BotonSimple" onclick="MostrarConfirmaciónTabla('<?php echo $value["prospecto_id"]; ?>', '<?php echo $value["usuario_id"]; ?>', '<?php echo $value["camp_id"]; ?>', '<?php echo $value["ejecutivo_id"]; ?>')">
                            <?php
                            }
                            else
                            {
                            ?>
                                <span class="BotonSimple" onclick="Ajax_CargarAccion_DetalleSolCred('<?php echo $value["prospecto_id"]; ?>', 0)">
                            <?php
                            }
                                echo 'Más Opc.';
                            ?>
                            </span>

                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            <?php
            }
            ?>
        </table>
    </div>

    <br />

    <?php
    if (isset($arrRespuesta[0])) 
    {
        if(count($arrRespuesta) < 2000)
        {
    ?>
            <a id="btnExportarPDF" class="BotonMinimalistaPequeno" style="width:100px!important;" onclick="return Exportar('pdf', '<?php echo $etapa_id; ?>');"> <?php echo $this->lang->line('reportes_exportar_pdf'); ?> </a>
            &nbsp;&nbsp;
        <?php
        }
        ?>
        <a id="btnExportarExcel" class="BotonMinimalistaPequeno" style="width:100px!important;" onclick="return Exportar('excel', '<?php echo $etapa_id; ?>');"> <?php echo $this->lang->line('reportes_exportar_excel'); ?> </a>
    <?php
    }
    ?>
</div>

<div id="tabla_resultado_opcion" style="width: 90%;">
    
    <div class="FormularioSubtitulo" style="float: none !important; text-align: center !important;">
        
        <i class="fa fa-binoculars" aria-hidden="true"></i> <span id="texto_codigo"></span>
        
    </div>
    
    <br /><br />
    
    <div style="clear: both"></div>
    
    <?php    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_PROSPECTO))
    {    
    ?>
    
        <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_DetalleProspectoReporte(MyApp.codigo)">
            <?php echo $this->lang->line('consulta_opciones_detalle'); ?>
        </span>
    <?php
    }
    ?>
    
    <?php    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
    {    
    ?>
        <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_DocumentoProspectoReporte(MyApp.codigo)">
            <?php echo $this->lang->line('consulta_opciones_documentos'); ?>
        </span>
    <?php
    }
    ?>
    
    <?php    
    //if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_EMPRESA))
    if(1==1)
    {    
    ?>
        <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_InformeFinal(MyApp.codigo)">
            <?php echo $this->lang->line('consulta_opciones_empresa'); ?>
        </span>
    <?php
    }
    ?>
    
    <?php    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_USUARIO))
    {    
    ?>
        <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_DetalleUsuarioReporte(0, MyApp.usuario)">
            <?php echo $this->lang->line('consulta_opciones_ejecutivo'); ?>
        </span>
    <?php
    }
    ?>
    
    <?php    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL))
    {    
    ?>
        <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_HistorialReporte(MyApp.codigo)">
            <?php echo $this->lang->line('consulta_opciones_observaciones'); ?>
        </span>
    <?php
    }
    ?>
    
    <?php    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_SEGUIMIENTO))
    {    
    ?>
        <span class="BotonSimpleGrande" onclick="Ajax_CargarAccion_SeguimientoProspectoReporte(MyApp.codigo)">
            <?php echo $this->lang->line('consulta_opciones_seguimiento'); ?>
        </span>
    <?php
    }
    ?>
    
    <div style="clear: both"></div>
    
    <br /><br />
    
    <div>
        <a onclick="OcultarConfirmaciónTabla();" class="BotonMinimalista"> Volver a la tabla </a>
    </div>
    
    <div style="clear: both"></div>

    <br />

    <?php if (isset($respuesta)) { ?>
        <div class="mensajeBD"> 
            <div style="padding: 15px;">
                <?php echo $respuesta ?>
            </div>
        </div>
    <?php } ?>

    <div id="divErrorListaResultado" class="mensajeBD"> </div>

</div>