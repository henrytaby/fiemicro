<link rel="stylesheet" href="<?php echo $this->config->base_url(); ?>html_public/js/select_multiple/virtual-select.min.css" />
<script src="<?php echo $this->config->base_url(); ?>html_public/js/select_multiple/virtual-select.min.js"></script>

<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Seguimiento/Resultado',
            'divResultadoReporte', 'divErrorListaResultado', 'SLASH');

    function LimpiarResultado(){
        $("#divResultadoReporte").html("");
    }

    function Volver(){
        $('html,body').animate({scrollTop: $('#divCargarFormulario').show().offset().top - 80}, 600);
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
            url: 'Seguimiento/Ver/PoblarLista',
            type: 'post',
            data: {
                tipo:tipo, opciones:opciones, aux:aux, estado:$('#estado').val()
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
    
    function Refresh_FiltraRegistrosLista()
    {
        var cnfrm = confirm('<?php echo $this->lang->line('seguimiento_Reporte_refresh_estado'); ?>');
        if(cnfrm != true)
        {
            return false;
        }
        else
        {
            // Cargar funciones iniciales
            FiltraRegistrosLista('departamento');
            FiltraRegistrosLista('agencia_default');
            FiltraRegistrosLista('oficial_default');
        }
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
                name: 'sel_departamento',
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
                name: 'sel_agencia',
                options: option_agencia,
                multiple: true,
                search: true,
                markSearchResults: true,
                disabledOptions: [-2],
                popupDropboxBreakpoint: '10px'
            });

            VirtualSelect.init({
                ele: '#sel_oficial',
                name: 'sel_oficial',
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
            
            $("#campoFiltroFechaDCDesde").datepicker({changeYear: true, changeMonth: true, onSelect: function(selected) {
                  $("#campoFiltroFechaDCHasta").datepicker("option","minDate", selected)}});
            $("#campoFiltroFechaDCHasta").datepicker({changeYear: true, changeMonth: true});
            $("#campoFiltroFechaDCDesde, #campoFiltroFechaDCHasta").attr("readonly", "readonly");

            // Cargar funciones iniciales
            FiltraRegistrosLista('departamento');
            FiltraRegistrosLista('agencia_default');
            FiltraRegistrosLista('oficial_default');
        });

    <?php
    }
    ?>
    
    
            
</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
        
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('SeguimientoTitulo') . $this->mfunciones_generales->GetValorCatalogo($_SESSION["identificador_tipo_perfil_app_tracking"], 'tipo_perfil_app') . $nombre_region; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('SeguimientoSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php
            if($flag_ie == 1)
            {
            ?>
                <div class="mensaje_resultado" style="width: 80%; text-align: center;"> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> 
                    Lo sentimos, tu explorador no soporta los filtros aplicados para este módulo. 
                    <br /> 
                    Para el óptimo funcionamiento de este módulo, por favor ingresa con un explorador actualizado (Chrome, Firefox, Safari, Opera, etc.).
                </div>
                    
            <?php
            }
            else
            {
            ?>
                <div style="display: block;" id="FiltrosReporte">

                    <table class="tablaresultados Mayuscula" style="width: 95%;" border="0">
                        
                        <tr class="FilaGris OpcionReporteGeneral">
                            
                            <td style="width:100%">
                                
                                <table style="width: 100%;" border="0">
                                    <tr>
                                        <td valign="middle" style="width: 20%; font-weight: bold;">
                                            <label style="margin-left: 0px;">
                                                
                                                Seleccione:
                                                <br /> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Estado
                                                <br /> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Tipo de Registro
                                                <br /> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Formato del Reporte
                                                <br /> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Filtros y Rango de Fechas
                                            </label>
                                        </td>
                                        
                                        <td valign="middle" style="width: 80%; font-weight: bold;">
                                            
                                            <div class="col-sm-4">

                                                <table style="width: 100%;" border="0">
                                                    <tr>
                                                        <td style="width: 70%; text-align: left; font-weight: bold;">

                                                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                                            Estado Agencia/Oficial
                                                            <span style="font-size: 1.2em;" class="EnlaceSimple" data-balloon-length="medium" data-balloon="La opción seleccionada para estado se aplicará en la generación del reporte. También puede hacer clic en la opción ‘Actualizar’ para volver a cargar los filtros y listar los registros que cumplan con este estado. No obstante, si hubiese seleccionado alguna agencia y/u oficial de negocios previamente, este filtro se aplicará con mayor prioridad en la generación del reporte." data-balloon-pos="left">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                            </span>

                                                        </td>
                                                        <td style="width: 30%; text-align: right; font-weight: bold;">
                                                            <span class="EnlaceSimple" onclick="Refresh_FiltraRegistrosLista();"> Actualizar <i class="fa fa-refresh" aria-hidden="true"></i></span>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <?php echo $arrCajasHTML["estado"]; ?>
                                            </div>
                                            
                                            <div class="col-sm-4">
                                                <table style="width: 100%;" border="0">
                                                    <tr>
                                                        <td style="width: 100%; text-align: left; font-weight: bold;">
                                                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                                            Tipo de Registro:
                                                        </td>
                                                    </tr>
                                                </table>
                                                <?php echo $arrCajasHTML["tipo_registro"]; ?>
                                            </div>
                                            
                                            <div class="col-sm-4">
                                                <table style="width: 100%;" border="0">
                                                    <tr>
                                                        <td style="width: 100%; text-align: left; font-weight: bold;">
                                                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                                            Formato del Reporte:
                                                        </td>
                                                    </tr>
                                                </table>
                                                <?php echo $arrCajasHTML["formato_reporte"]; ?>
                                            </div>
                                            
                                        </td>
                                        
                                    </tr>

                                </table>
                                
                            </td>
                            
                        </tr>
                        
                        <tr class="FilaGris OpcionReporteGeneral">
                            <td style="width:100%">

                                <div class="col-sm-3">

                                    <table style="width: 100%;" border="0">
                                        <tr>
                                            <td style="width: 50%; text-align: left; font-weight: bold;">
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
                                                    Fecha de Check Visita:
                                                </label>
                                            </td>
                                            <td valign="middle" style="width: 35%;">
                                                <strong> Desde el: &nbsp;&nbsp;<span title="Limpiar" class="EnlaceSimple" onclick="$('#campoFiltroFechaDesde').val(''); return false;"> <i class="fa fa-trash-o" aria-hidden="true"></i> </span>
                                                    <input type="text" id="campoFiltroFechaDesde" name="campoFiltroFechaDesde" />
                                                </strong>
                                            </td>
                                            <td valign="middle" style="width: 35%;">
                                                <strong> Hasta el: &nbsp;&nbsp;<span title="Limpiar" class="EnlaceSimple" onclick="$('#campoFiltroFechaHasta').val(''); return false;"> <i class="fa fa-trash-o" aria-hidden="true"></i> </span>
                                                    <input type="text" id="campoFiltroFechaHasta" name="campoFiltroFechaHasta" />
                                                </strong>
                                            </td>
                                        </tr>
                                        
                                    </table>

                                </div>

                                <div class="col-sm-3" style="text-align: center;">
                                    <br />
                                    <a id="btnGuardarDatosLista" class="BotonMinimalista" style="width: 100%;" onclick="LimpiarResultado();"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
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

        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <br /><br />
        
        <div id="divResultadoReporte">
        
        </div>
        
    </div>
    
    
</div>

<?php
// Borrar las variables de SESSION
if(isset($_SESSION['arrResultadoSeguimiento']))
{
    $_SESSION['arrResultadoSeguimiento'] = '';
    $_SESSION['arrResultadoSeguimiento_filtro'] = '';
}
?>