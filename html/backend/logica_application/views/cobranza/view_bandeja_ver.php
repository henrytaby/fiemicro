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
                
            case 'normalizador':
                
                opciones = $('#sel_agencia').val();

                txt1 = '<?php echo $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app'); ?>';
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
                
            case 'normalizador_default':
            
                opciones = [-1];
                tipo = 'normalizador';
                aux = 1;
                
                txt1 = '<?php echo $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app'); ?>';
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
            FiltraRegistrosLista('normalizador_default');
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
                ele: '#sel_normalizador',
                name: 'sel_normalizador',
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
            
            $("#campoFiltroFechaCheckDesde").datepicker({changeYear: true, changeMonth: true, onSelect: function(selected) {
                  $("#campoFiltroFechaCheckHasta").datepicker("option","minDate", selected)}});
            $("#campoFiltroFechaCheckHasta").datepicker({changeYear: true, changeMonth: true});
            $("#campoFiltroFechaCheckDesde, #campoFiltroFechaCheckHasta").attr("readonly", "readonly");
            
            $("#campoFiltroFechaVisitaDesde").datepicker({changeYear: true, changeMonth: true, onSelect: function(selected) {
                  $("#campoFiltroFechaVisitaHasta").datepicker("option","minDate", selected)}});
            $("#campoFiltroFechaVisitaHasta").datepicker({changeYear: true, changeMonth: true});
            $("#campoFiltroFechaVisitaDesde, #campoFiltroFechaVisitaHasta").attr("readonly", "readonly");

            $("#campoFiltroFechaCompDesde").datepicker({changeYear: true, changeMonth: true, onSelect: function(selected) {
                  $("#campoFiltroFechaCompHasta").datepicker("option","minDate", selected)}});
            $("#campoFiltroFechaCompHasta").datepicker({changeYear: true, changeMonth: true});
            $("#campoFiltroFechaCompDesde, #campoFiltroFechaCompHasta").attr("readonly", "readonly");

            // Cargar funciones iniciales
            FiltraRegistrosLista('departamento');
            FiltraRegistrosLista('agencia_default');
            FiltraRegistrosLista('normalizador_default');
        });

    <?php
    }
    ?>
    
    // Filtros personalizados
    
    function Ajax_GenerarReporte(dato_vista) {
        
        LimpiarResultado();
        
        var filtros = [];
        $("#tbParametrosReporte>tbody>tr").each(function () {filtros.push(JSON.parse($(this).attr("datos")));});
        var parametros = {"vista":dato_vista, "filtros":filtros, 
            "tipo_bandeja":"<?php echo $tipo_bandeja; ?>", 
            "estado":$('#estado').val(), 
            "consolidado":$('#consolidado').val(), 
            "formato_reporte":$('#formato_reporte').val(), 
            "sel_departamento":$('#sel_departamento').val(), 
            "sel_agencia":$('#sel_agencia').val(), 
            "sel_normalizador":$('#sel_normalizador').val(), 
            "campoFiltroFechaDesde":$('#campoFiltroFechaDesde').val(), 
            "campoFiltroFechaHasta":$('#campoFiltroFechaHasta').val()};
                
        var strParametros = "&parametros=" + encodeURIComponent(JSON.stringify(parametros));
        Ajax_CargadoGeneralPagina('<?php echo ($tipo_bandeja=='supervision' ? 'Bandeja/SupNorm/Generar' : 'Tracking/Norm/Generar'); ?>', 'divResultadoReporte', "divErrorListaResultado", 'SLASH', strParametros);
    }

    function AgregarFiltroNorm() {    
        Ajax_CargadoGeneralPagina('Bandeja/SupNorm/AgregarFiltro', 'divElementoFlotante', "divErrorBusqueda", '', "&tipo_bandeja=<?php echo $tipo_bandeja; ?>");
    }

    function QuitarFiltro(obj) {
        $(obj).closest("tr").remove();
    }

    function RegistraFiltro(filtro) {
        var nuevoFiltro = $('<tr><td class="campoFiltroReportes"></td><td class="valorFiltroReportes"></td><td class="accionesFiltroReportes">' +
            '<button title="Quitar filtro" onclick="QuitarFiltro(this);"><i class="fa fa-trash" aria-hidden="true" title="Eliminar"></i></button>' +
            '</td></tr>');
        $(nuevoFiltro).find(".campoFiltroReportes").html("<b>"+filtro.titulo+":</b> ");
        $(nuevoFiltro).find(".valorFiltroReportes").html(filtro.descripcion);
        nuevoFiltro.attr("campo",filtro.campo);
        nuevoFiltro.attr("datos",JSON.stringify(filtro));
        $("#tbParametrosReporte").find("[campo="+filtro.campo+"]").remove();
        $("#tbParametrosReporte").append(nuevoFiltro);
    }
            
</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
        
            <?php
            $nombre_bandeja = $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app');
            
            switch ($tipo_bandeja) {
                case 'supervision':

                    $texto_titulo = sprintf($this->lang->line('NormSupervisionTitulo'), $nombre_bandeja) . $nombre_region;
                    $texto_subtitulo = sprintf($this->lang->line('NormSupervisionSubtitulo'), $nombre_bandeja, $nombre_bandeja, $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app_singular'));

                    break;
                
                case 'tracking':

                    $texto_titulo = sprintf($this->lang->line('NormSeguimientoTitulo'), $nombre_bandeja) . $nombre_region;
                    $texto_subtitulo = sprintf($this->lang->line('NormSeguimientoSubtitulo'), $nombre_bandeja, $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app_singular'), $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app_singular'));

                    break;

                default:
                    break;
            }
            
            ?>
        
            <div class="FormularioSubtitulo"> <?php echo $texto_titulo; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $texto_subtitulo; ?></div>
        
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
                                                
                                                <?php
                                                if($tipo_bandeja == 'tracking')
                                                {
                                                    echo '<br /> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Formato del Reporte';
                                                }
                                                ?>
                                                
                                                <br /> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Filtros y Rango de Fechas
                                            </label>
                                        </td>
                                        
                                        <td valign="middle" style="width: 80%; font-weight: bold;">
                                            
                                            <div class="col-sm-4">

                                                <table style="width: 100%;" border="0">
                                                    <tr>
                                                        <td style="width: 70%; text-align: left; font-weight: bold;">

                                                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                                            Estado Agencia/Normalizador
                                                            <span style="font-size: 1.2em;" class="EnlaceSimple" data-balloon-length="medium" data-balloon="La opción seleccionada para estado se aplicará en la generación del reporte. También puede hacer clic en la opción ‘Actualizar’ para volver a cargar los filtros y listar los registros que cumplan con este estado. No obstante, si hubiese seleccionado alguna Agencia y/o <?php echo $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app_singular'); ?> previamente, este filtro se aplicará con mayor prioridad en la generación del reporte." data-balloon-pos="top">
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
                                                            Estado Registro (Consolidado | No Consolidado):
                                                        </td>
                                                    </tr>
                                                </table>
                                                <select id="consolidado" name="consolidado">
                                                    <option value="99" mitext="Todos">Todos</option>
                                                    <option value="0" mitext="Abierta/Activo">No Consolidado</option>
                                                    <option value="1" mitext="Cerrada/Inactivo">Consolidado</option>
                                                </select>
                                            </div>
                                            
                                            <?php
                                            if($tipo_bandeja != 'supervision')
                                            {
                                            ?>
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
                                            <?php
                                            }
                                            ?>
                                            
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

                                                <span id="txt_normalizador">
                                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                </span>

                                                <?php echo $this->mfunciones_generales->GetValorCatalogo(3, 'tipo_perfil_app_singular'); ?>:

                                            </td>
                                            <td style="width: 50%; text-align: right; font-weight: bold;">

                                                <span class="EnlaceSimple" onclick="FiltraRegistrosLista('normalizador')">Filtrar por Agencia(s) <i class="fa fa-filter" aria-hidden="true"></i></span>

                                            </td>
                                        </tr>
                                    </table>

                                    <div id="sel_normalizador"></div>
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
                                                    Fecha de Registro del Caso:
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

                                <div class="col-sm-3" style="text-align: center; font-weight: bold;">
                                    <br /><br />
                                    <span class="EnlaceSimple" onclick="AgregarFiltroNorm();">  <i class="fa fa-long-arrow-down" aria-hidden="true"></i> AGREGAR NUEVO FILTRO </span>
                                </div>

                            </td>
                        </tr>
                        
                        <tr class="FilaGris OpcionReporteGeneral">
                            <td valign=top" style="width:100%; padding: 0px 15px;">
                                
                                <table id="tbParametrosReporte" class="tablaresultados TextoMayuscula" style="border: 0px; width: 100% !important;">
                                    <tbody>
                                    </tbody>
                                </table>
                                
                            </td>
                        </tr>
                            
                    </table>

                    <br />
                    
                    <div class="col-sm-3" style="text-align: center; float: right; margin-right: 20px;">
                        <a class="BotonMinimalista" style="width: 100%;" onclick="Ajax_GenerarReporte();"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
                    </div>
                    
                </div>
            
                <div style="clear: both"></div>
                
            <?php
            }
            ?>
        
        </form>
        
        <div style="clear: both"></div>

        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <div id="divResultadoReporte">
        
        </div>
        
    </div>
    
    
</div>

<?php
// Borrar las variables de SESSION sólo para el tipo Seguimiento
if($tipo_bandeja == 'tracking')
{
    if(isset($_SESSION['arrResultadoSeguimiento']))
    {
        $_SESSION['arrResultadoSeguimiento'] = '';
        $_SESSION['arrResultadoSeguimiento_filtro'] = '';
    }
}
?>