<script type="text/javascript">

    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": <?php echo PAGINADO_TABLA; ?>,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[0, "asc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
    $("#confirmacion").hide();
    var idFormularioEliminar = "";
    var idInstanciaEliminar = "";
    function mostrarConfirmacion (instanciaId, formularioId) {
      console.log('====================_MENSAJE_A_MOSTRARSE_====================')
      console.log(instanciaId, formularioId)
      console.log('====================_MENSAJE_A_MOSTRARSE_====================')
      idFormularioEliminar = formularioId;
      idInstanciaEliminar = instanciaId;
      console.log('====================_MENSAJE_A_MOSTRARSE_====================')
      console.log($('#confirmacion'))
      console.log('====================_MENSAJE_A_MOSTRARSE_====================')
      $("#confirmacion").fadeIn(500);
      $("#divCargarFormulario").hide();
    }

    function OcultarConfirmación()
    {
        $("#divCargarFormulario").fadeIn(500);    
        $("#confirmacion").hide();
    }
    function Ajax_CargarAccion_EditarInstancia(idInstancia) {
      var strParametros = "&idInstancia=" + idInstancia;
      Ajax_CargadoGeneralPagina('Formularios/mostrar_instancia', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }

    function Ajax_CargarAccion_EliminarInstancia() {
      var strParametros = "&idInstancia=" + idInstanciaEliminar + "&idFormulario=" + idFormularioEliminar;
      Ajax_CargadoGeneralPagina('Formularios/eliminar_instancia', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
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
  background-color: #8BC34A;
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
        <div class="Botones3Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Formularios/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
        <div id="divErrorBusqueda" class="mensajeBD">
        </div>
        <?php
        $i = 0;
        $strClase = "FilaBlanca";
        $nroColumnas = 5;
        $conteo = 0;
        echo '<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
            <thead>
                <tr class="FilaCabecera">
                    <th style="width:5%;">Nº </th>';
                    foreach ($cabeceras as $cabecera) {
                      if ($nroColumnas > $conteo) {
                        $conteo++;
                        echo '<th style="width:10%;">'.$cabecera.'</th>';
                      }
                    }
            echo    '<th style="width:15%;">'.$this->lang->line('TablaOpciones').'</th>
              </tr>
            </thead>
            <tbody>';
                foreach ($instancias as $instancia)
                {
                $i++;
                $conteo = 0;
                echo '
                <tr class="'.$strClase.'">
                    <td style="text-align: center;">'.$i.'</td>';
                      foreach ($instancia->values as $value) {
                        $valorInstancia = $value->value; 
                        if (strlen($valorInstancia) < 200) {
                          if ($nroColumnas > $conteo) {
                            $conteo++;
                            echo '<td style="text-align: center;">'.$valorInstancia.'</td>';
                          }
                        } else {
                          if ($nroColumnas > $conteo) {
                            $conteo++;
                            echo '<td style="text-align: center;"><img width="100" height="100" src="data:image/png;base64,'.$valorInstancia.'"></td>';
                          }
                        }
                        // echo '<td style="text-align: center;">'.strlen($valorInstancia) > 200 ? '<img width="100" height="100" src='+ $valorInstancia +'>'  : $valorInstancia.'</td>';
                      }
              echo '<td style="text-align: center;">
                        <button class="btn orange" onclick="Ajax_CargarAccion_EditarInstancia('.$instancia->id.')"><i class="fa fa-pencil"></i></button>
                        <button class="btn red" onclick="mostrarConfirmacion('.$instancia->id.', '.$idFormulario.')"><i class="fa fa-trash"></i></button>
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
    <a onclick="OcultarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?></a>
  </div>
  <div class="Botones2Opciones">
    <a onclick="Ajax_CargarAccion_EliminarInstancia();" class="BotonMinimalista"><?php echo $this->lang->line('BotonAceptar'); ?> </a>
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
