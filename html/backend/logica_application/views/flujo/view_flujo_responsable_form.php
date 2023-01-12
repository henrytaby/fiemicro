<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Afiliador/Registro/Guardar',
            'divVistaMenuPantalla', 'divErrorListaResultado');

    function GuardarRolesUsuarios(codigo) {
        
        var cnfrm = confirm('¿Está seguro de guardar la información de la asociación de las listas?');
        if(cnfrm != true)
        {
            return false;
        }
        else
        {
            $('#spanActoresActualizado').fadeIn();
            
            var strParametros = ' &estructura_id=' + codigo;
            Ajax_CargadoGeneralPagina('Flujo/Asignar/Guardar', 'divElementoFlotante', 'divErrorListaResultado', '', strParametros);
        }
    }
    
    function AdicionarRegistroListaRol() {
        
        var catalogo_rol = $("#catalogo_rol").val();
        
        if(catalogo_rol == -1 || catalogo_rol == '')
        {
            alert('Debe seleccionar por lo menos un Rol.');
        }
        else
        {
            var strParametro = " &catalogo_rol=" + catalogo_rol;
            Ajax_CargadoGeneralPagina('Flujo/ListaRol/Adicionar', 'divListaRol', 'divErrorListaResultado', 'SLASH', strParametro);

            $('#catalogo_rol').val("");
            $('.custom-combobox').find('input:text').val('');
        }
    }
    
    function QuitarRegistroListaRol(posicion) {
        
        var cnfrm = confirm('¿Está seguro de quitar el Rol?');
        if(cnfrm != true)
        {
            return false;
        }
        else
        {
            var strParametro = "&posicion=" + posicion;
            Ajax_CargadoGeneralPagina('Flujo/ListaRol/Quitar', 'divListaRol', 'divErrorListaResultado', 'SLASH', strParametro);
        }
    }
    
    function AdicionarRegistroListaUsuario() {
        
        var catalogo_usuario = $("#catalogo_usuario").val();
        
        if(catalogo_usuario == -1 || catalogo_usuario == '')
        {
            alert('Debe seleccionar por lo menos un Usuario.');
        }
        else
        {
            var strParametro = " &catalogo_usuario=" + catalogo_usuario;
            Ajax_CargadoGeneralPagina('Flujo/ListaUsuario/Adicionar', 'divListaUsuario', 'divErrorListaResultado', 'SLASH', strParametro);

            $('#catalogo_usuario').val("");
            $('.custom-combobox').find('input:text').val('');
        }
    }
    
    function QuitarRegistroListaUsuario(posicion) {
        
        var cnfrm = confirm('¿Está seguro de quitar el Usuario?');
        if(cnfrm != true)
        {
            return false;
        }
        else
        {
            var strParametro = "&posicion=" + posicion;
            Ajax_CargadoGeneralPagina('Flujo/ListaUsuario/Quitar', 'divListaUsuario', 'divErrorListaResultado', 'SLASH', strParametro);
        }
    }
    
    document.getElementById("divContenidoElementoFlotante").style.top = "40px";
    
    Ajax_CargadoGeneralPagina('Flujo/ListaRol/Lista', 'divListaRol', 'divErrorListaResultado', '', '');
    Ajax_CargadoGeneralPagina('Flujo/ListaUsuario/Lista', 'divListaUsuario', 'divErrorListaResultado', '', '');
    
    $('#spanActoresActualizado').hide();
    
    $('#catalogo_rol option[value=-1]').val("");
    $("#catalogo_rol").combobox();
    $('.custom-combobox-input').attr("placeholder", "--Seleccione el valor o escriba para filtrar--");
    
    $('#catalogo_usuario option[value=-1]').val("");
    $("#catalogo_usuario").combobox();
    $('.custom-combobox-input').attr("placeholder", "--Seleccione el valor o escriba para filtrar--");

</script>

<div id="FormularioVentanaAuxiliar" style="overflow-y: auto; height: 500px;">

    <br /><br />
    
        <?php if($arrRespuesta[0]["etapa_categoria"]=="50") { $tipo_aux = "Notificación"; } else { $tipo_aux = "Etapa"; } ?>
    
        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('FlujoActoresTitulo') . '<br />' . $tipo_aux . ': ' . $arrRespuesta[0]["etapa_nombre"]; ?></div>
        <div class="FormularioSubtituloComentarioNormal" style="width: 100% !important;">
            <?php 
            
                if($arrRespuesta[0]["etapa_id"] == 13) 
                {
                    echo "<br />Seleccione los Roles y/o Usuarios que recibirán las notificaciones de los registros cuando se encuentren atrasados. Los listados se guardarán temporalmente hasta que proceda con el guardado.<br /><br />Si selecciona un usuario que ya es parte de un Rol registrado, sólo será notificado 1 vez. A estos usuarios se les enviará la notificación; la notificación obedecerá a la regionalización, por lo que si el prospecto es de una Agencia no asignada a los usuarios registrados en las listas, no serán notificados."; 
                    
                }
                else
                {
                    echo $this->lang->line('FlujoActoresSubtitulo') . ($arrRespuesta[0]["etapa_id"] == 7 ? ' Esta etapa no está sujeta a Regionalización, notificará a todos los usuarios que establezca en las listas.' : 'la notificación obedecerá a la regionalización (Agencias), por lo que si el Cliente es de una Agencia no asignada a los usuarios registrados en las listas, no serán notificados.');
                }
            ?>
        
        </div>

    <div style="clear: both"></div>

    <form id="FormularioRegistroLista" method="post">

        <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

        <input type="hidden" name="redireccionar" value="" />

        <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["etapa_id"])){ echo $arrRespuesta[0]["etapa_id"]; } ?>" />

        <div style="text-align: center;">
            <a onclick="GuardarRolesUsuarios('<?php if(isset($arrRespuesta[0]["etapa_id"])){ echo $arrRespuesta[0]["etapa_id"]; } ?>')" class="BotonMinimalista"> Guardar Roles y/o Usuarios </a>
        </div>
        
        <br /><br />
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
    
        <br />
        
    <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

        <?php $strClase = "FilaBlanca"; ?>

        <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
        <tr class="FilaGris">

            <td valign="top" style="width: 100%; font-weight: bold; text-align: right;">
            
                <div class="divOpciones" style="padding: 5px 5px !important;">
                
                    Seleccione el Rol:

                    <span class="EnlaceSimple" onclick="AdicionarRegistroListaRol()">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar
                    </span>

                    <br />

                    <?php                                            
                        if(count($arrRoles[0]) > 0)
                        {
                            $valor_parent = '-1';

                            echo html_select('catalogo_rol', $arrRoles, 'rol_id', 'rol_nombre', '', $valor_parent);
                        }
                        else
                        {
                            echo $this->lang->line('TablaNoRegistros');
                        }
                    ?>

                    <br />

                    <div id="divListaRol"> </div>
                
                </div>
                
                <div class="divOpciones" style="padding: 5px 5px !important;">
                    Seleccione el Usuario Específico: 
                
                    <span class="EnlaceSimple" onclick="AdicionarRegistroListaUsuario()">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar
                    </span>

                    <br />

                        <?php                                            
                            if(count($arrUsuarios[0]) > 0)
                            {
                                $valor_parent = '-1';

                                echo html_select('catalogo_usuario', $arrUsuarios, 'usuario_id', 'nombre_completo', '', $valor_parent);
                            }
                            else
                            {
                                echo $this->lang->line('TablaNoRegistros');
                            }
                        ?>

                    <br />

                    <div id="divListaUsuario"> </div>
                </div>
                
            </td>

        </tr>

    </table>

    </form>

    <br /><br /><br />

    <div style="clear: both"></div>



</div>