<link rel="stylesheet" href="<?php echo $this->config->base_url(); ?>html_public/js/select_multiple/virtual-select.min.css" />
<script src="<?php echo $this->config->base_url(); ?>html_public/js/select_multiple/virtual-select.min.js"></script>

<script type="text/javascript">

    function VerTablaFiltros(id) {
        if($("#"+id).is(':hidden')){
            $("#"+id).slideDown();
            $('html,body').animate({scrollTop: $("#FormularioOpcionesReporte").show().offset().top - 80}, 600);
        }
        else
        {
            $("#"+id).slideUp();
        }
    }

    function LimpiarFechas() {
        $("#fecha_inicio").val('');
        $("#fecha_fin").val('');
    }

    function FiltraRegistrosLista(tipo)
    {
        var opciones = '';
        
        var txt1 = '';
        var txt2 = '';
        
        var aux = 0;
        
        switch (tipo)
        {
            case 'agencia':
                
                opciones = $('#sel_departamento').val();
                
                txt1 = 'Agencias';
                txt2 = 'Departamento';

                break;
                
            case 'oficial':
                
                opciones = $('#sel_agencia').val();

                txt1 = 'Oficiales de Negocios';
                txt2 = 'Agencia';

                break;

            case 'departamento':
            
                opciones = 'default';
                
                txt1 = 'Departamentos';
                txt2 = 'Registros';
                
                break;

            case 'agencia_default':
            
                opciones = [-1];
                tipo = 'agencia';
                aux = 1;
                
                txt1 = 'Agencias';
                txt2 = 'Registros';
                
                break;
                
            case 'oficial_default':
            
                opciones = [-1];
                tipo = 'oficial';
                aux = 1;
                
                txt1 = 'Oficiales de Negocios';
                txt2 = 'Registros';
                
                break;

            default:

                alert("Acción No Válida.");
                return false;

                break;
        }
        
        if(opciones == '')
        {
            alert("Por favor debe seleccionar al menos una opción (" + txt2 + ") para filtrar.");
            return false;
        }
        
        $.ajax({
            url: 'Dashboard/Ver/PoblarLista',
            type: 'post',
            data: {
                tipo:tipo, opciones:opciones, aux:aux
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                if(len>0)
                {
                    document.querySelector('#sel_'+tipo).setOptions(response);
                    
                    if(tipo != 'departamento')
                    {
                        $('#txt_'+tipo).html('<i class="fa fa-check-square-o" aria-hidden="true"></i>');
                    }
                }
                else
                {
                    document.querySelector('#sel_'+tipo).setOptions([]);
                    
                    if(tipo != 'departamento')
                    {
                        $('#txt_'+tipo).html('<i class="fa fa-exclamation-circle" aria-hidden="true"></i>');
                    }

                    alert("No se encontraron " + txt1 + " con las opciones (" + txt2 + ") seleccionadas para el filtrado.");
                }
            }
        });
    }
    
    function ReporteParamertos() {
        
        var strParametros = "&sel_departamento=" + $("#sel_departamento").val()
            + "&sel_agencia=" + $("#sel_agencia").val()
            + "&sel_oficial=" + $("#sel_oficial").val()
            + "&campoFiltroFechaDesde=" + $("#campoFiltroFechaDesde").val()
            + "&campoFiltroFechaHasta=" + $("#campoFiltroFechaHasta").val();
    
            $("#sel_departamento_tabla").val($("#sel_departamento").val());
            $("#sel_agencia_tabla").val($("#sel_agencia").val());
            $("#sel_oficial_tabla").val($("#sel_oficial").val());
            $("#campoFiltroFechaDesde_tabla").val($("#campoFiltroFechaDesde").val());
            $("#campoFiltroFechaHasta_tabla").val($("#campoFiltroFechaHasta").val());
    
        return strParametros;
    }
    
    function ReporteFunnel() {
        
        $("#divResultadoFunnel").html("");
        
        var strParametros = ReporteParamertos();
        Ajax_CargadoGeneralPagina('Dashboard/Reporte/Funnel', 'divResultadoFunnel', "divErrorBusqueda", '', strParametros);
        
        $('html,body').animate({scrollTop: $("#FormularioOpcionesReporte").show().offset().top - 80}, 600);
    }

    <?php
    
    $ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
    if (preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0') !== false && strpos($ua, 'rv:11.0') !== false))
    {
        $flag_ie = 1;
    }
    else
    {
        $flag_ie = 0;
    ?>
        $(document).ready(function(){

            option_departamento = [
                { label: 'CHUQUISACA', value: '1' },
                { label: 'LA PAZ', value: '102' },
                { label: 'LA PAZ - EL ALTO', value: '115' },
                { label: 'COCHABAMBA', value: '545' },
                { label: 'ORURO', value: '723' },
                { label: 'POTOSÍ', value: '884' },
                { label: 'TARIJA', value: '1134' },
                { label: 'SANTA CRUZ', value: '1225' },
                { label: 'BENI', value: '1419' },
                { label: 'PANDO', value: '1468' },
            ];

            VirtualSelect.init({
                ele: '#sel_departamento',
                options: option_departamento,
                multiple: true,
                search: false,
                popupDropboxBreakpoint: '10px',
            });

            option_agencia = [
                { label: 'Seleccione el/los Departamento/s para Filtrar', value: '-2' },
            ];

            option_ficial = [
                { label: 'Seleccione la/s Agencia/s para Filtrar', value: '-2' },
            ];

            VirtualSelect.init({
                ele: '#sel_agencia',
                options: option_agencia,
                multiple: true,
                search: true,
                markSearchResults: true,
                disabledOptions: [-2],
                popupDropboxBreakpoint: '10px'
            });

            VirtualSelect.init({
                ele: '#sel_oficial',
                options: option_ficial,
                multiple: true,
                search: true,
                markSearchResults: true,
                disabledOptions: [-2],
                popupDropboxBreakpoint: '10px'
            });

            $("#campoFiltroFechaDesde").datepicker({changeYear: true, changeMonth: true, onSelect: function(selected) {
                  $("#campoFiltroFechaHasta").datepicker("option","minDate", selected)}});
            $("#campoFiltroFechaHasta").datepicker({changeYear: true, changeMonth: true});

            $("#campoFiltroFechaDesde, #campoFiltroFechaHasta").attr("readonly", "readonly");

            // Cargar funciones iniciales
            FiltraRegistrosLista('departamento');
            FiltraRegistrosLista('agencia_default');
            FiltraRegistrosLista('oficial_default');
        });

    <?php
    }
    ?>

    //ReporteFunnel();

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"><?php echo $this->lang->line('TituloDashboard') . $nombre_region; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('SubDashboard'); ?></div>


        <div style="clear: both;"> </div>
            
        <br />
        
        <form id="FormularioOpcionesReporte" method="post">
            
            <input type="hidden" name="sel_departamento_tabla" id="sel_departamento_tabla" />
            <input type="hidden" name="sel_agencia_tabla" id="sel_agencia_tabla" />
            <input type="hidden" name="sel_oficial_tabla" id="sel_oficial_tabla" />
            <input type="hidden" name="campoFiltroFechaDesde_tabla" id="campoFiltroFechaDesde_tabla" />
            <input type="hidden" name="campoFiltroFechaHasta_tabla" id="campoFiltroFechaHasta_tabla" />
            
            <?php
            if($flag_ie == 1)
            {
            ?>
                <input type="hidden" name="sel_departamento" id="sel_departamento" />
                <input type="hidden" name="sel_agencia" id="sel_agencia" />
                <input type="hidden" name="sel_oficial" id="sel_oficial" />
                <input type="hidden" name="campoFiltroFechaDesde" id="campoFiltroFechaDesde" />
                <input type="hidden" name="campoFiltroFechaHasta" id="campoFiltroFechaHasta" />
                
                <div class="mensaje_resultado" style="width: 80%; text-align: center;"> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Lo sentimos, tu explorador no soporta algunas funcionalidades de este módulo. <br /> Para el óptimo funcionamiento de este módulo, por favor ingresa con un explorador actualizado (Chrome, Firefox, Safari, Opera, etc.). </div>
                
            <?php
            }
            else
            {
            ?>
                <table class="tablaresultados Mayuscula" style="width: 95%;" border="0">
                    <tr class="FilaGris OpcionReporteGeneral">
                        <td style="width:100%; font-weight: bold; text-align: right; padding-right: 15px;">
                            <span class="EnlaceSimple" onclick="VerTablaFiltros('FiltrosReporte')">
                                <i class="fa fa-cog" aria-hidden="true"></i> <?php echo $this->lang->line('dashboard_filtros'); ?>
                            </span>
                        </td>
                    </tr>
                </table>

                <br />

                <div style="display: block;" id="FiltrosReporte">

                    <table class="tablaresultados Mayuscula" style="width: 95%;" border="0">
                        <tr class="FilaGris OpcionReporteGeneral">
                            <td style="width:100%">

                                <div class="col-sm-3">

                                    <table style="width: 100%;" border="0">
                                        <tr>
                                            <td style="width: 50%; text-align: left; font-weight: bold">
                                                <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                                Departamento:
                                            </td>
                                            <td style="width: 50%; text-align: right; font-weight: bold;">

                                            </td>
                                        </tr>
                                    </table>

                                    <div id="sel_departamento"></div>
                                </div>

                                <div class="col-sm-4">

                                    <table style="width: 100%;" border="0">
                                        <tr>
                                            <td style="width: 35%; text-align: left; font-weight: bold;">

                                                <span id="txt_agencia">
                                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                </span>

                                                Agencia:

                                            </td>
                                            <td style="width: 65%; text-align: right; font-weight: bold;">

                                                <span class="EnlaceSimple" onclick="FiltraRegistrosLista('agencia')">Filtrar por Departamento(s) <i class="fa fa-filter" aria-hidden="true"></i></span>

                                            </td>
                                        </tr>
                                    </table>

                                    <div id="sel_agencia"></div>
                                </div>

                                <div class="col-sm-4">

                                    <table style="width: 100%;" border="0">
                                        <tr>
                                            <td style="width: 50%; text-align: left; font-weight: bold;">

                                                <span id="txt_oficial">
                                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                </span>

                                                Oficial de Negocios:

                                            </td>
                                            <td style="width: 50%; text-align: right; font-weight: bold;">

                                                <span class="EnlaceSimple" onclick="FiltraRegistrosLista('oficial')">Filtrar por Agencia(s) <i class="fa fa-filter" aria-hidden="true"></i></span>

                                            </td>
                                        </tr>
                                    </table>

                                    <div id="sel_oficial"></div>
                                </div>

                            </td>
                        </tr>
                        <tr class="FilaGris OpcionReporteGeneral">
                            <td style="width:100%">

                                <div class="col-sm-8">

                                    <table style="width: 100%;" border="0">
                                        <tr>
                                            <td valign="middle" style="width: 30%;">
                                                <label style="margin-left: 0px;">
                                                    <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                                    Fecha de Asignación:
                                                </label>
                                            </td>
                                            <td valign="middle" style="width: 35%;">
                                                <strong> Desde el: &nbsp;&nbsp;<span title="Limpiar" class="EnlaceSimple" onclick="$('#campoFiltroFechaDesde').val(''); return false;"> <i class="fa fa-trash-o" aria-hidden="true"></i> </span>
                                                    <input type="text" id="campoFiltroFechaDesde"/>
                                                </strong>
                                            </td>
                                            <td valign="middle" style="width: 35%;">
                                                <strong> Hasta el: &nbsp;&nbsp;<span title="Limpiar" class="EnlaceSimple" onclick="$('#campoFiltroFechaHasta').val(''); return false;"> <i class="fa fa-trash-o" aria-hidden="true"></i> </span>
                                                    <input type="text" id="campoFiltroFechaHasta"/>
                                                </strong>
                                            </td>
                                        </tr>
                                    </table>

                                </div>

                                <div class="col-sm-3" style="text-align: center;">

                                    <br />

                                    <span style="width: 100%;" class="BotonMinimalista" onclick="ReporteFunnel()">
                                        <i class="fa fa-filter" aria-hidden="true"></i> Generar Reporte
                                    </span>
                                </div>

                            </td>
                        </tr>
                    </table>

                </div>
                
            <?php
            }
            ?>
                
        </form>

        <div style="clear: both"></div>
        <?php // Si hay un error PHP o DB se mostra´ra en este DIV ?>
        <div id="divErrorBusqueda" class="mensajeBD">  </div>
        <div style="clear: both"></div>
        
        <div style="clear: both"></div>
        <div id="divResultadoFunnel"></div>
        
        <div style="clear: both; height: 50px;"></div>
        
    </div>
</div>
