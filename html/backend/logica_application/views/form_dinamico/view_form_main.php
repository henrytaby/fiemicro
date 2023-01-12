<script type="text/javascript">

    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": <?php echo PAGINADO_TABLA; ?>,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[0, "asc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
var idFormularioEliminar = "";

$("#confirmacion").hide();

function mostrarConfirmacion (formularioId) {
  idFormularioEliminar = formularioId;
  $("#confirmacion").fadeIn(500);    
  $("#divCargarFormulario").hide();
}

function OcultarConfirmación()
{
    $("#divCargarFormulario").fadeIn(500);    
    $("#confirmacion").hide();
}

function Ajax_CargarAccion_Editar(codigo) {
  var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
  Ajax_CargadoGeneralPagina('Tarea/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
}


function Ajax_CargarAccion_InstanciasFormulario(idFormulario) {
  var strParametros = '&idFormulario=' + idFormulario;
  Ajax_CargadoGeneralPagina('Formularios/listar_instancias', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
}

function Ajax_CargarAccion_EditarFormulario(idFormulario) {
  var strParametros = '&idFormulario=' + idFormulario;
  Ajax_CargadoGeneralPagina('Formularios/mostrar', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
}

function Ajax_CargarAccion_NuevoCasoFormulario(idFormulario) {
  var strParametros = '&idFormulario=' + idFormulario;
  Ajax_CargadoGeneralPagina('Formularios/nuevoCaso', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
}

function Ajax_CargarAccion_PublicarFormulario(idFormulario) {
  var strParametros = '&idFormulario=' + idFormulario;
  Ajax_CargadoGeneralPagina('Formularios/publicar', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
}

function Ajax_CargarAccion_DespublicarFormulario(idFormulario) {
  var strParametros = '&idFormulario=' + idFormulario;
  Ajax_CargadoGeneralPagina('Formularios/despublicar', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
}


function Ajax_CargarAccion_EliminarFormulario() {
  var strParametros = '&idFormulario=' + idFormularioEliminar;
  Ajax_CargadoGeneralPagina('Formularios/eliminar', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
   $("#divCargarFormulario").fadeIn(500);    
  $("#confirmacion").hide();
}
</script>
<style>
.red {
  background-color: #b71c1c;
}
.orange {
  background-color: #FF9800;
}
.green {
  background-color: #006699;
}
.btn {
  border: none;
  color: white;
  padding: 8px 12px;
  font-size: 12px;
  cursor: pointer;
}
</style>
<?php $cantidad_columnas = 1;?>
<div id="divVistaMenuPantalla" align="center">
    <div id="divCargarFormulario" class="TamanoContenidoGeneral">
        <br /><br />
        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
        
        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('FormularioDinamicoTitulo'); ?></div>
        <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('FormularioDinamicoSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        <div align="left" class="BotonesVariasOpciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Formulario/configurarFormulario')">
                <?php echo $this->lang->line('FormularioDinamicoNuevo'); ?>
            </span>
        </div>
        <!-- <div align="center" class="BotonesVariasOpciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarAccion_EditarFormulario('1')">
                <?php echo $this->lang->line('FormularioDinamicoEdicion'); ?>
            </span>
        </div>
        <div align="right" class="BotonesVariasOpciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarAccion_NuevoCasoFormulario('1')">
                <?php echo $this->lang->line('FormularioDinamicoNuevoCaso'); ?>
            </span>
        </div> -->
        <div id="divErrorBusqueda" class="mensajeBD">
        </div>
        <?php
        $i = 0;
        $strClase = "FilaBlanca";
        echo '<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
            <thead>
                <tr class="FilaCabecera">
                    <th style="width:5%;">Nº </th>
                    <th style="width:10%;">'.$this->lang->line('FormularioDinamicoNombre').'</th>
                    <th style="width:15%;">'.$this->lang->line('FormularioDinamicoDescripcion').'</th>
                    <th style="width:15%;">'.$this->lang->line('FormularioDinamicoPublicado').'</th>
                    <th style="width:30%;">'.$this->lang->line('TablaOpciones').'</th>
                </tr>
            </thead>
            <tbody>';
                foreach ($formularios as $formulario)
                {
                $i++;
                echo '
                <tr class="'.$strClase.'">
                    <td style="text-align: center;">'.$i.'</td>
                    <td style="text-align: center;">'.$formulario->nombre.'</td>
                    <td style="text-align: center;">'.$formulario->descripcion.'</td>';

                    // echo <td style="text-align: center;"></td>';
                     if ($formulario->publicado) {
                      echo '<td style="text-align: center;"><button class="btn orange" onclick="Ajax_CargarAccion_DespublicarFormulario('.$formulario->id.')">Publicado</button></td>';
                    } else {
                      echo '<td style="text-align: center;"><button class="btn green" onclick="Ajax_CargarAccion_PublicarFormulario('.$formulario->id.')">Despublicado</button></td>';
                    }
                    echo '<td style="text-align: center;">
                        <button class="btn green" onclick="Ajax_CargarAccion_InstanciasFormulario('.$formulario->id.')"><i class="fa fa-eye"></i></button>';
                        if (!$formulario->publicado) {
                          
                          echo '<button class="btn orange" onclick="Ajax_CargarAccion_EditarFormulario('.$formulario->id.')"><i class="fa fa-pencil"></i></button>';
                        } else {
                          echo '<button class="btn green" onclick="Ajax_CargarAccion_NuevoCasoFormulario('.$formulario->id.')"><i class="fa fa-plus"></i></button>';
                        }
                        echo '<button class="btn red" onclick="mostrarConfirmacion('.$formulario->id.')"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>';
                }
                
                echo '
            </tbody>
        </table>';
        ?>
    </div>
</div>

<div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

<div class="FormularioSubtituloImagenPregunta"></div>

<div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
<div class="PreguntaTexto "><?php echo $this->lang->line('conf_formulario_eliminar'); ?></div>

<div style="clear: both"></div>

<br />

<div class="PreguntaConfirmar">
  <?php echo $this->lang->line('PreguntaContinuar'); ?>
</div>

<div class="Botones2Opciones">
  <a onclick="OcultarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?>
  </a>
</div>

<div class="Botones2Opciones">
  <a onclick="Ajax_CargarAccion_EliminarFormulario();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
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