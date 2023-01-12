<form id="AgregarFiltroForm" method="post" action="">
    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
        <?php $strClase = "FilaGris"; ?>
        <tr class="<?php echo $strClase; ?>">
            <td style="min-width:80px;">
                <strong> <?php echo $this->lang->line('reportes_opciones_filtro'); ?> </strong>
            </td>
            <td style="width: 100%;">
                <select id="campoFiltro" onchange="ActualizaOpcionCampoFiltro();">
                    <?php foreach ($campos as $opcion): ?>
                        <option tipo="<?php echo $opcion->tipo ?>"
                                value="<?php echo $opcion->campo ?>"><?php echo $opcion->titulo ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr class="FiltroReporteFilaFecha ui-helper-hidden">
            <td colspan="2">
                <table style="width:100%">
                    <tr>

                        <td style="width: 50%;" valign="bottom">
                            <strong> Desde el: <input type="text" id="campoFiltroFechaDesde"/>
                                <button title="Limpiar" style="float:right"
                                        onclick="$('#campoFiltroFechaDesde').val(''); return false;"><i
                                            class="fa fa-trash" aria-hidden="true" title="Eliminar"></i></button>
                            </strong>

                        </td>
                        <td style="width: 50%;" valign="bottom">
                            <strong> Hasta el:
                                <input type="text" id="campoFiltroFechaHasta"/>
                                <button title="Limpiar" style="float:right"
                                        onclick="$('#campoFiltroFechaHasta').val(''); return false;"><i
                                            class="fa fa-trash" aria-hidden="true" title="Eliminar"></i></button>
                            </strong>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="FiltroReporteFilaTexto ui-helper-hidden">
            <td colspan="2">
                <input type="text" id="campoFiltroTexto"/>
            </td>
        </tr>
        <tr class="FiltroReporteFilaLista ui-helper-hidden">
            <td colspan="2">
                <ul id="campoFiltroLista" style="max-height:400px;overflow-y: auto; overflow-x: hidden">
                </ul>
                <a onclick="MarcarOpcionesFiltroLista()" id="FiltroReporteFilaListaBotonMarcar" style="float:right; font-weight: bold;"><i class="fa fa-object-group" aria-hidden="true"></i> Marcar/Desmarcar todos</a>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="divErrorFormulario" class="mensajeBD"></div>
            </td>
        </tr>
    </table>
    
    <br />
    
    <button id="btnGuardarFiltro" class="BotonMinimalistaPequeno" style="display:inline-block; float:right;" onclick="return GuardarFiltro();"> <?php echo $this->lang->line('reportes_boton_agregar_filtro'); ?> </button>

</form>

<script type="text/javascript">
    function MarcarOpcionesFiltroLista(){
        var opciones = $('#campoFiltroLista').find("input[type=checkbox]");
        var opcionesMarcadas = $('#campoFiltroLista').find("input[type=checkbox]:checked");
        if (opciones.length == opcionesMarcadas.length) {
            // Desmarcar
            opciones.prop("checked",false);
            opciones.attr("checked",false);
        } else
        {
            // Marcar
            opciones.prop("checked",true);
            opciones.attr("checked",true);
        }
    }
    function getCampoSeleccionado() {
        var opcion = $("#AgregarFiltroForm #campoFiltro>option:selected");
        var campo = $(opcion).attr("value");
        var tipo = $(opcion).attr("tipo");
        var titulo = $(opcion).html();
        return {"campo": campo, "tipo": tipo, "titulo": titulo};
    }

    function CargarOpcionesFiltroLista(campo) {
        var perfil_app = $("input:radio[name=perfil_app]:checked").val();
        var c=0;
        $.getJSON("<?php echo site_url("Consultas/ValoresFiltro")?>",{"campo":campo, "perfil_app":perfil_app}, function (data) {
            $(data).each(function () {
                c++;
                var item = $('<li><input id="opcionLista'+c+'" name="opcionLista'+c+'" type="checkbox" class="" value="'+this.id+'" />' +
                    '<label for="opcionLista'+c+'" class=""><span></span>'+this.descripcion+'</label></li>');
                $('#campoFiltroLista').append(item);
            });
        });
    }

    function CheckExclusivo(obj) {
        var activo = $(obj).is(":checked");
        var opciones = $('#campoFiltroLista').find("input[type=checkbox]").not(obj);
        opciones.prop("checked",!activo);
        opciones.attr("checked",!activo);
    }

    function CargarOpcionesSiNo() {
        var c=0;
        var item_si = $('<li><input id="opcionLista1" name="opcionLista1" type="checkbox" class="" value="1"/>' +
            '<label for="opcionLista1" class=""><span></span>Sí</label></li>');
        $('#campoFiltroLista').append(item_si);

        var item_no = $('<li><input id="opcionLista2" name="opcionLista2" type="checkbox" class="" value="0" />' +
            '<label for="opcionLista2" class=""><span></span>No</label></li>');
        $('#campoFiltroLista').append(item_no);
        $('#campoFiltroLista').find("input").bind("change",function () { CheckExclusivo(this);});
        var primeraOpcion = $('#campoFiltroLista').find("input[type=checkbox]").first();
        primeraOpcion.prop("checked",true);
        primeraOpcion.attr("checked",true);
    }

    function ActualizaOpcionCampoFiltro() {
        var campoActual = $("#AgregarFiltroForm").attr("campo_seleccionado");
        var opcion = getCampoSeleccionado();
        var opcionStr = JSON.stringify(opcion);
        if (opcionStr == campoActual) return;
        $("#AgregarFiltroForm").attr("campo_seleccionado", opcionStr);
        $("#AgregarFiltroForm").find("tr.FiltroReporteFilaFecha,tr.FiltroReporteFilaTexto,tr.FiltroReporteFilaLista").addClass("ui-helper-hidden");
        $('#campoFiltroFechaDesde').val('');
        $('#campoFiltroFechaHasta').val('');
        $('#campoFiltroTexto').val('');
        $('#campoFiltroLista').html("");
        $("#divErrorFormulario").html("");
        $("#FiltroReporteFilaLista a").removeClass("ui-helper-hidden");
        switch (opcion.tipo) {
            case "id":
            case "texto":
                $("#AgregarFiltroForm tr.FiltroReporteFilaTexto").removeClass("ui-helper-hidden");
                break;
            case "fecha":
                $("#AgregarFiltroForm tr.FiltroReporteFilaFecha").removeClass("ui-helper-hidden");
                break;
            case "booleano":
                $("#FiltroReporteFilaListaBotonMarcar").addClass("ui-helper-hidden");
                $("#AgregarFiltroForm tr.FiltroReporteFilaLista").removeClass("ui-helper-hidden");
                CargarOpcionesSiNo(opcion.campo);
                break;
            case "lista":
                $("#AgregarFiltroForm tr.FiltroReporteFilaLista").removeClass("ui-helper-hidden");
                CargarOpcionesFiltroLista(opcion.campo);
                break;
        }
    }

    function ValidarFiltro(campo) {
        $("#divErrorFormulario").html("");
        switch (campo.tipo) {
            case "id":
                $('#campoFiltroTexto').val($('#campoFiltroTexto').val().trim())
                if ($('#campoFiltroTexto').val().length == 0) {
                    $("#divErrorFormulario").html("<br />Debe indicar el código por el cual se filtrara el campo<br /><br />");
                    return false;
                }
                if (!isPositiveInteger($('#campoFiltroTexto').val())) {
                    $("#divErrorFormulario").html("<br />El código debe ser un numero entero mayor a cero<br /><br />");
                    return false;
                }
                break;
            case "texto":
                $('#campoFiltroTexto').val($('#campoFiltroTexto').val().trim())
                if ($('#campoFiltroTexto').val().length == 0) {
                    $("#divErrorFormulario").html("<br />Debe indicar el texto por el cual se filtrara el campo<br /><br />");
                    return false;
                }
                break;
            case "booleano":
            case "lista":
                var opcionesMarcadas = $('#campoFiltroLista').find("input[type=checkbox]:checked");
                if ($('#campoFiltroLista').find("input[type=checkbox]:checked").length == 0) {
                    $("#divErrorFormulario").html("<br />Debe marcar al menos una opción<br /><br />");
                    return false;
                }
                break;
            case "fecha":
                if ($('#campoFiltroFechaDesde').val().length == 0 && $('#campoFiltroFechaHasta').val().length == 0) {
                    $("#divErrorFormulario").html("Debe indicar al menos una de las dos fechas");
                    return false;
                }
                break;
        }
        return true;
    }

    function isPositiveInteger(n) {
        return n >>> 0 === parseFloat(n);
    }

    function GuardarFiltro() {
        var campo = getCampoSeleccionado();
        if (!ValidarFiltro(campo)) return false;
        switch (campo.tipo) {
            case "id":
            case "texto":
                var valor = $('#campoFiltroTexto').val();
                var descripcion = "<b>Contenga </b>"+$('#campoFiltroTexto').val();
                RegistraFiltro({"campo":campo.campo,titulo:campo.titulo,"descripcion":descripcion,"tipo":campo.tipo,"valor":valor});
                break;
            case "booleano":
            case "lista":
                var valores = [];
                var descripciones = [];
                $('#campoFiltroLista').find("input[type=checkbox]:checked").each(function () {
                    valores.push($(this).val());
                    descripciones.push($(this).parent().text());
                });
                var descripcion = "<b>Sea </b>"+descripciones.join(" <b>ó</b> ");
                RegistraFiltro({"campo":campo.campo,"titulo":campo.titulo,"descripcion":descripcion,"tipo":campo.tipo,"valores":valores});
                break;
            case "fecha":
                var desde = $('#campoFiltroFechaDesde').val();
                var hasta = $('#campoFiltroFechaHasta').val();
                var descripcion = ((desde.length>0?"<b>Desde el </b> " + desde:"") + (hasta.length>0?" <b>Hasta el </b> " + hasta:"")).trim();
                RegistraFiltro({"campo":campo.campo,"titulo":campo.titulo,"descripcion":descripcion,"tipo":campo.tipo,"desde":desde,"hasta":hasta});
                break;
        }
        Elementos_General_MostrarElementoFlotante(false);
        return false;
    }

    ActualizaOpcionCampoFiltro();

    $("#campoFiltroFechaHasta,#campoFiltroFechaDesde").attr("readonly", "readonly");
    
    $(document).ready(function(){
        $("#campoFiltroFechaDesde").datepicker({changeYear: true, changeMonth: true, onSelect: function(selected) {
              $("#campoFiltroFechaHasta").datepicker("option","minDate", selected)
        }});
        $("#campoFiltroFechaHasta").datepicker({changeYear: true, changeMonth: true});
    });
    
</script>