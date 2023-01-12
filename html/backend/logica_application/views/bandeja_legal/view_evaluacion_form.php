<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Legal/Evaluacion/Guardar',
            'divVistaMenuPantalla', 'divErrorListaResultado');

    $("#divCargarFormulario").show();    
    $("#confirmacion").hide();

    function MostrarConfirmación()
    {
        $("#divCargarFormulario").hide();
        $("#confirmacion").fadeIn(500);
    }
    
    function OcultarConfirmación()
    {
        $("#divCargarFormulario").fadeIn(500);    
        $("#confirmacion").hide();
    }

    function Ajax_CargarAccion_DetalleUsuario(tipo_codigo, codigo_usuario) {
        var strParametros = "&tipo_codigo=" + tipo_codigo + "&codigo_usuario=" + codigo_usuario;
        Ajax_CargadoGeneralPagina('Usuario/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function VerTablaEvaluacion(elem, id) {

        if(elem.value == 2){
            $("#"+id).slideDown(450, function() {
                $(this).removeClass("ocultar_elemento");
            });
        }
        else{
            $("#"+id).slideUp(450, function() {
                $(this).addClass("ocultar_elemento");
            });
        }
    }

    $("#evaluacion_ci_fecnac").datepicker({changeYear: true, changeMonth: true});
    $("#evaluacion_fecha_testimonio").datepicker({changeYear: true, changeMonth: true});
    $("#evaluacion_fundaempresa_emision").datepicker({changeYear: true, changeMonth: true});
    $("#evaluacion_fundaempresa_vigencia").datepicker({changeYear: true, changeMonth: true});
    $("#evaluacion_fecha_testimonio_poder").datepicker({changeYear: true, changeMonth: true});
    
    $(document).ready(function(){
        $("#evaluacion_fecha_solicitud").datepicker({changeYear: true, changeMonth: true, onSelect: function(selected) {
              $("#evaluacion_fecha_evaluacion").datepicker("option","minDate", selected)
        }});
        $("#evaluacion_fecha_evaluacion").datepicker({changeYear: true, changeMonth: true});
    });

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('EvalLegalTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('EvalLegalSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["prospecto_id"])){ echo $arrRespuesta[0]["prospecto_id"]; } ?>" />
            
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaGris"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('prospecto_id'); ?>
                </td>

                <td style="width: 70%;">
                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleProspecto('<?php echo $arrRespuesta[0]["prospecto_id"]; ?>')">
                        <?php echo PREFIJO_PROSPECTO . $arrRespuesta[0]["prospecto_id"]; ?>                                    
                    </span>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('prospecto_nombre_empresa'); ?> Empresa
                </td>

                <td style="width: 70%;">
                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleEmpresa('<?php echo $arrRespuesta[0]["empresa_id"]; ?>')">
                    <?php echo $arrRespuesta[0]["empresa_nombre"]; ?>
                    </span>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_categoria_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrRespuesta[0]["empresa_categoria"]; ?>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('ejecutivo_asignado_nombre'); ?>
                </td>

                <td style="width: 70%;">
                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleUsuario(0, '<?php echo $arrRespuesta[0]["usuario_id"]; ?>')">
                        <?php echo $arrRespuesta[0]["ejecutivo_asignado_nombre"]; ?>
                    </span>                    
                </td>
            </tr>
            
        </table>
        
        <br />

        <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 40%; font-weight: bold;">
                    <?php echo $this->lang->line('evaluacion_denominacion_comercial'); ?>
                </td>

                <td style="width: 60%;">                    
                    
                    <?php echo $arrCajasHTML["evaluacion_denominacion_comercial"]; ?>
                    
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 40%; font-weight: bold;">
                    <?php echo $this->lang->line('evaluacion_razon_social'); ?>
                </td>

                <td style="width: 60%;">                    
                    
                    <?php echo $arrCajasHTML["evaluacion_razon_social"]; ?>
                    
                </td>
            </tr>
            
         </table>
        
        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

            <?php $strClase = "FilaGris"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 40%; font-weight: bold;">
                    <?php echo $this->lang->line('evaluacion_fotocopia_nit'); ?>
                </td>

                <td style="width: 60%;">                    
                    
                    <?php echo $arrCajasHTML["evaluacion_doc_nit"]; ?>
                    
                </td>
            </tr>
            
         </table>
        
        
        <?php        
            $clase_ocultar = 'class="ocultar_elemento"';
            if(isset($arrResultado[0]['evaluacion_doc_nit']) && $arrResultado[0]['evaluacion_doc_nit'] == 2)
            {
                $clase_ocultar = '';
            }        
        ?>
        
        <div id="1" <?php echo $clase_ocultar; ?> style="width: 90%;">
            
            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php $strClase = "FilaBlanca"; ?>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_nit'); ?>
                    </td>

                    <td style="width: 60%;">
                        <?php echo $arrCajasHTML["evaluacion_nit"]; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_representante_legal'); ?>
                    </td>

                    <td style="width: 60%;">
                        <?php echo $arrCajasHTML["evaluacion_representante_legal"]; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_razon_idem'); ?>
                    </td>

                    <td style="width: 60%;">
                        
                        <div style="float: left;">
                        
                            <?php
                            $seleccion1 = '';
                            $seleccion2 = 'checked="checked"';
                            if(isset($arrResultado[0]['evaluacion_razon_idem']) && $arrResultado[0]['evaluacion_razon_idem'] == 1)
                            {
                                $seleccion1 = 'checked="checked"';
                                $seleccion2 = '';
                            }                            
                            ?>
                            
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion1-si" name="evaluacion_razon_idem" type="radio" <?php echo $seleccion1; ?> class="" value="1" />
                            <label for="seleccion1-si" class=""><span></span>Si</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>

                        <div style="float: left;">

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion1-no" name="evaluacion_razon_idem" type="radio" <?php echo $seleccion2; ?> class="" value="0" />
                            <label for="seleccion1-no" class=""><span></span>No</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>
                        
                    </td>
                </tr>
                
            </table>
            
        </div>
        
        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

            <?php $strClase = "FilaGris"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 40%; font-weight: bold;">
                    <?php echo $this->lang->line('evaluacion_fotocopia_certificado'); ?>
                </td>

                <td style="width: 60%;">                    
                    
                    <?php echo $arrCajasHTML["evaluacion_doc_certificado"]; ?>
                    
                </td>
            </tr>
            
        </table>
        
        <?php        
            $clase_ocultar = 'class="ocultar_elemento"';
            if(isset($arrResultado[0]['evaluacion_doc_certificado']) && $arrResultado[0]['evaluacion_doc_certificado'] == 2)
            {
                $clase_ocultar = '';
            }        
        ?>
        
        <div id="2" <?php echo $clase_ocultar; ?> style="width: 90%;">
            
            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                
                <?php $strClase = "FilaBlanca"; ?>
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_actividad_principal'); ?>
                    </td>

                    <td style="width: 60%;">
                        <?php echo $arrCajasHTML["evaluacion_actividad_principal"]; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_actividad_secundaria'); ?>
                    </td>

                    <td style="width: 60%;">
                        <?php echo $arrCajasHTML["evaluacion_actividad_secundaria"]; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_razon_idem'); ?>
                    </td>

                    <td style="width: 60%;">
                        
                        <div style="float: left;">
                        
                            <?php
                            $seleccion1 = '';
                            $seleccion2 = 'checked="checked"';
                            if(isset($arrResultado[0]['evaluacion_certificado_idem']) && $arrResultado[0]['evaluacion_certificado_idem'] == 1)
                            {
                                $seleccion1 = 'checked="checked"';
                                $seleccion2 = '';
                            }                            
                            ?>
                            
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion2-si" name="evaluacion_certificado_idem" type="radio" <?php echo $seleccion1; ?> class="" value="1" />
                            <label for="seleccion2-si" class=""><span></span>Si</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>

                        <div style="float: left;">

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion2-no" name="evaluacion_certificado_idem" type="radio" <?php echo $seleccion2; ?> class="" value="0" />
                            <label for="seleccion2-no" class=""><span></span>No</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>
                        
                    </td>
                </tr>
                
            </table>
            
        </div>
        
        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

            <?php $strClase = "FilaGris"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 40%; font-weight: bold;">
                    <?php echo $this->lang->line('evaluacion_fotocopia_ci'); ?>
                </td>

                <td style="width: 60%;">                    
                    
                    <?php echo $arrCajasHTML["evaluacion_doc_ci"]; ?>
                    
                </td>
            </tr>
            
        </table>
        
        <?php        
            $clase_ocultar = 'class="ocultar_elemento"';
            if(isset($arrResultado[0]['evaluacion_doc_ci']) && $arrResultado[0]['evaluacion_doc_ci'] == 2)
            {
                $clase_ocultar = '';
            }        
        ?>
        
        <div id="3" <?php echo $clase_ocultar; ?> style="width: 90%;">
            
            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php $strClase = "FilaBlanca"; ?>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_ci_pertenece'); ?>
                    </td>

                    <td style="width: 60%;">
                        <?php echo $arrCajasHTML["evaluacion_ci_pertenece"]; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_ci_vigente'); ?>
                    </td>

                    <td style="width: 60%;">
                        
                        <div style="float: left;">
                        
                            <?php
                            $seleccion1 = '';
                            $seleccion2 = 'checked="checked"';
                            if(isset($arrResultado[0]['evaluacion_ci_vigente']) && $arrResultado[0]['evaluacion_ci_vigente'] == 1)
                            {
                                $seleccion1 = 'checked="checked"';
                                $seleccion2 = '';
                            }                            
                            ?>
                            
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion3-si" name="evaluacion_ci_vigente" type="radio" <?php echo $seleccion1; ?> class="" value="1" />
                            <label for="seleccion3-si" class=""><span></span>Si</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>

                        <div style="float: left;">

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion3-no" name="evaluacion_ci_vigente" type="radio" <?php echo $seleccion2; ?> class="" value="0" />
                            <label for="seleccion3-no" class=""><span></span>No</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>
                        
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_ci_fecnac'); ?>
                    </td>

                    <td style="width: 60%;">
                        
                        <?php echo $arrCajasHTML["evaluacion_ci_fecnac"]; ?>
                        
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_ci_titular'); ?>
                    </td>

                    <td style="width: 60%;">
                        
                        <?php echo $arrCajasHTML["evaluacion_ci_titular"]; ?>
                        
                    </td>
                </tr>
                
            </table>
            
        </div>
        
        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

            <?php $strClase = "FilaGris"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 40%; font-weight: bold;">
                    <?php echo $this->lang->line('evaluacion_fotocopia_testimonio'); ?>
                </td>

                <td style="width: 60%;">                    
                    
                    <?php echo $arrCajasHTML["evaluacion_doc_test"]; ?>
                    
                </td>
            </tr>
            
        </table>
        
        <?php        
            $clase_ocultar = 'class="ocultar_elemento"';
            if(isset($arrResultado[0]['evaluacion_doc_test']) && $arrResultado[0]['evaluacion_doc_test'] == 2)
            {
                $clase_ocultar = '';
            }        
        ?>
        
        <div id="4" <?php echo $clase_ocultar; ?> style="width: 90%;">
            
            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php $strClase = "FilaBlanca"; ?>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_numero_testimonio'); ?>
                    </td>

                    <td style="width: 60%;">
                        <?php echo $arrCajasHTML["evaluacion_numero_testimonio"]; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_duracion_empresa'); ?>
                    </td>

                    <td style="width: 60%;">
                        
                        <?php echo $arrCajasHTML["evaluacion_duracion_empresa"]; ?>
                        
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_fecha_testimonio'); ?>
                    </td>

                    <td style="width: 60%;">
                        
                        <?php echo $arrCajasHTML["evaluacion_fecha_testimonio"]; ?>
                        
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_objeto_constitucion'); ?>
                    </td>

                    <td style="width: 60%;">
                        
                        <?php echo $arrCajasHTML["evaluacion_objeto_constitucion"]; ?>
                        
                    </td>
                </tr>
                
            </table>
            
        </div>
        
        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

            <?php $strClase = "FilaGris"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 40%; font-weight: bold;">
                    <?php echo $this->lang->line('evaluacion_fotocopia_poder'); ?>
                </td>

                <td style="width: 60%;">                    
                    
                    <?php echo $arrCajasHTML["evaluacion_doc_poder"]; ?>
                    
                </td>
            </tr>
            
        </table>
        
        <?php        
            $clase_ocultar = 'class="ocultar_elemento"';
            if(isset($arrResultado[0]['evaluacion_doc_poder']) && $arrResultado[0]['evaluacion_doc_poder'] == 2)
            {
                $clase_ocultar = '';
            }        
        ?>
        
        <div id="5" <?php echo $clase_ocultar; ?> style="width: 90%;">
            
            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php $strClase = "FilaBlanca"; ?>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_fecha_testimonio_poder'); ?>
                    </td>

                    <td style="width: 60%;">
                        <?php echo $arrCajasHTML["evaluacion_fecha_testimonio_poder"]; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_numero_testimonio_poder'); ?>
                    </td>

                    <td style="width: 60%;">
                        <?php echo $arrCajasHTML["evaluacion_numero_testimonio_poder"]; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_firma_conjunta'); ?>
                    </td>

                    <td style="width: 60%;">
                        
                        <div style="float: left;">
                        
                            <?php
                            $seleccion1 = '';
                            $seleccion2 = 'checked="checked"';
                            if(isset($arrResultado[0]['evaluacion_firma_conjunta']) && $arrResultado[0]['evaluacion_firma_conjunta'] == 1)
                            {
                                $seleccion1 = 'checked="checked"';
                                $seleccion2 = '';
                            }                            
                            ?>
                            
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion4-si" name="evaluacion_firma_conjunta" type="radio" <?php echo $seleccion1; ?> class="" value="1" />
                            <label for="seleccion4-si" class=""><span></span>Si</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>

                        <div style="float: left;">

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion4-no" name="evaluacion_firma_conjunta" type="radio" <?php echo $seleccion2; ?> class="" value="0" />
                            <label for="seleccion4-no" class=""><span></span>No</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>
                        
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_facultad_representacion'); ?>
                    </td>

                    <td style="width: 60%;">
                        
                        <div style="float: left;">
                        
                            <?php
                            $seleccion1 = '';
                            $seleccion2 = 'checked="checked"';
                            if(isset($arrResultado[0]['evaluacion_facultad_representacion']) && $arrResultado[0]['evaluacion_facultad_representacion'] == 1)
                            {
                                $seleccion1 = 'checked="checked"';
                                $seleccion2 = '';
                            }                            
                            ?>
                            
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion5-si" name="evaluacion_facultad_representacion" type="radio" <?php echo $seleccion1; ?> class="" value="1" />
                            <label for="seleccion5-si" class=""><span></span>Si</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>

                        <div style="float: left;">

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion5-no" name="evaluacion_facultad_representacion" type="radio" <?php echo $seleccion2; ?> class="" value="0" />
                            <label for="seleccion5-no" class=""><span></span>No</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>
                        
                    </td>
                </tr>
                
            </table>
            
        </div>
        
        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

            <?php $strClase = "FilaGris"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 40%; font-weight: bold;">
                    <?php echo $this->lang->line('evaluacion_fotocopia_fundaempresa'); ?>
                </td>

                <td style="width: 60%;">                    
                    
                    <?php echo $arrCajasHTML["evaluacion_doc_funde"]; ?>
                    
                </td>
            </tr>
            
        </table>
        
        <?php        
            $clase_ocultar = 'class="ocultar_elemento"';
            if(isset($arrResultado[0]['evaluacion_doc_funde']) && $arrResultado[0]['evaluacion_doc_funde'] == 2)
            {
                $clase_ocultar = '';
            }        
        ?>
        
        <div id="6" <?php echo $clase_ocultar; ?> style="width: 90%;">
            
            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php $strClase = "FilaBlanca"; ?>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_fundaempresa_emision'); ?>
                    </td>

                    <td style="width: 60%;">
                        <?php echo $arrCajasHTML["evaluacion_fundaempresa_emision"]; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_fundaempresa_vigencia'); ?>
                    </td>

                    <td style="width: 60%;">
                        <?php echo $arrCajasHTML["evaluacion_fundaempresa_vigencia"]; ?>
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_idem_escritura'); ?>
                    </td>

                    <td style="width: 60%;">
                        
                        <div style="float: left;">
                        
                            <?php
                            $seleccion1 = '';
                            $seleccion2 = 'checked="checked"';
                            if(isset($arrResultado[0]['evaluacion_idem_escritura']) && $arrResultado[0]['evaluacion_idem_escritura'] == 1)
                            {
                                $seleccion1 = 'checked="checked"';
                                $seleccion2 = '';
                            }                            
                            ?>
                            
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion6-si" name="evaluacion_idem_escritura" type="radio" <?php echo $seleccion1; ?> class="" value="1" />
                            <label for="seleccion6-si" class=""><span></span>Si</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>

                        <div style="float: left;">

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion6-no" name="evaluacion_idem_escritura" type="radio" <?php echo $seleccion2; ?> class="" value="0" />
                            <label for="seleccion6-no" class=""><span></span>No</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>
                        
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_idem_poder'); ?>
                    </td>

                    <td style="width: 60%;">
                        
                        <div style="float: left;">
                        
                            <?php
                            $seleccion1 = '';
                            $seleccion2 = 'checked="checked"';
                            if(isset($arrResultado[0]['evaluacion_idem_poder']) && $arrResultado[0]['evaluacion_idem_poder'] == 1)
                            {
                                $seleccion1 = 'checked="checked"';
                                $seleccion2 = '';
                            }                            
                            ?>
                            
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion7-si" name="evaluacion_idem_poder" type="radio" <?php echo $seleccion1; ?> class="" value="1" />
                            <label for="seleccion7-si" class=""><span></span>Si</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>

                        <div style="float: left;">

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion7-no" name="evaluacion_idem_poder" type="radio" <?php echo $seleccion2; ?> class="" value="0" />
                            <label for="seleccion7-no" class=""><span></span>No</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>
                        
                    </td>
                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 40%; font-weight: bold;">
                        <?php echo $this->lang->line('evaluacion_idem_general'); ?>
                    </td>

                    <td style="width: 60%;">
                        
                        <div style="float: left;">
                        
                            <?php
                            $seleccion1 = '';
                            $seleccion2 = 'checked="checked"';
                            if(isset($arrResultado[0]['evaluacion_idem_general']) && $arrResultado[0]['evaluacion_idem_general'] == 1)
                            {
                                $seleccion1 = 'checked="checked"';
                                $seleccion2 = '';
                            }                            
                            ?>
                            
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion8-si" name="evaluacion_idem_general" type="radio" <?php echo $seleccion1; ?> class="" value="1" />
                            <label for="seleccion8-si" class=""><span></span>Si</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>

                        <div style="float: left;">

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <input id="seleccion8-no" name="evaluacion_idem_general" type="radio" <?php echo $seleccion2; ?> class="" value="0" />
                            <label for="seleccion8-no" class=""><span></span>No</label>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </div>
                        
                    </td>
                </tr>
                
            </table>
            
        </div>
        
        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">

                    <?php echo $this->lang->line('evaluacion_resultado'); ?>
                    <br /><br />

                    <?php echo $arrCajasHTML["evaluacion_resultado"]; ?>
                </td>
                
            </tr>
            
        </table>
        
        <br />
        
        <table class="tblListas Centrado" style="width: 90%;" border="0">
                
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaCabecera">
                <th style="width:70%;">
                    <strong><?php echo $this->lang->line('evaluacion_titulo_opcion'); ?></strong>
                </td>

                <th style="width:30%;">
                    
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            
            <?php
            
            $seleccion_conclusion = 0;
            if(isset($arrResultado[0]['opcion_conclusion']))
            {
                $seleccion_conclusion = $arrResultado[0]['opcion_conclusion'];
            }                            
            ?>
            
            <tr class="FilaBlanca">
                <td style="text-align: justify;">
                    <strong><?php echo $this->lang->line('evaluacion_conclusion1'); ?></strong>
                </td>

                <td style="text-align: center;">
                    <input id="opcion_conclusion1" name="opcion_conclusion" type="radio" class="" value="1" <?php if($seleccion_conclusion==1){ echo 'checked="checked"'; } ?> />
                    <label for="opcion_conclusion1" class=""><span></span>Seleccionar</label>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaBlanca">
                <td style="text-align: justify;">
                    <strong><?php echo $this->lang->line('evaluacion_conclusion2'); ?></strong>
                </td>

                <td style="text-align: center;">
                    <input id="opcion_conclusion2" name="opcion_conclusion" type="radio" class="" value="2" <?php if($seleccion_conclusion==2){ echo 'checked="checked"'; } ?> />
                    <label for="opcion_conclusion2" class=""><span></span>Seleccionar</label>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaBlanca">
                <td style="text-align: justify;">
                    <strong><?php echo $this->lang->line('evaluacion_conclusion3'); ?></strong>
                </td>

                <td style="text-align: center;">
                    <input id="opcion_conclusion3" name="opcion_conclusion" type="radio" class="" value="3" <?php if($seleccion_conclusion==3){ echo 'checked="checked"'; } ?> />
                    <label for="opcion_conclusion3" class=""><span></span>Seleccionar</label>
                </td>
            </tr>            
            
        </table>
        
        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 40%; font-weight: bold;">
                    <?php echo $this->lang->line('evaluacion_pertenece_regional'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="<?php echo $this->lang->line('evaluacion_regional_ayuda'); ?>" data-balloon-pos="right"> </span>
                </td>

                <td style="width: 60%;">
                    <?php
                        
                        $codigo_region = "";

                        if(isset($arrResultado[0]["evaluacion_pertenece_regional"]))
                        { 
                            $codigo_region = $arrResultado[0]["evaluacion_pertenece_regional"];
                        }

                        if(isset($arrRegional[0]))
                        {
                            echo html_select('evaluacion_pertenece_regional', $arrRegional, 'estructura_regional_id', 'estructura_regional_nombre', 'SINSELECCIONAR', $codigo_region);
                        }

                    ?>
                </td>
            </tr>
            
        </table>
        
        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 40%; font-weight: bold;">
                    <?php echo $this->lang->line('evaluacion_fecha_solicitud'); ?>
                </td>

                <td style="width: 60%;">
                    <?php echo $arrCajasHTML["evaluacion_fecha_solicitud"]; ?>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 40%; font-weight: bold;">
                    <?php echo $this->lang->line('evaluacion_fecha_evaluacion'); ?>
                </td>

                <td style="width: 60%;">
                    <?php echo $arrCajasHTML["evaluacion_fecha_evaluacion"]; ?>
                </td>
            </tr>
            
        </table>
        
        <br />
        
        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            
            <?php
            
                $direccion_bandeja = 'Menu/Principal';
            
                if(isset($_SESSION['direccion_bandeja_actual']))
                {
                    $direccion_bandeja = $_SESSION['direccion_bandeja_actual'];
                }
            
            ?>
            
            <a onclick="Ajax_CargarOpcionMenu('<?php echo $direccion_bandeja; ?>');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('evaluacion_Pregunta'); ?></div>
        
            <div style="clear: both"></div>
        
            <br />

        <div class="PreguntaConfirmar">
            <?php echo $this->lang->line('PreguntaContinuar'); ?>
        </div>

        <div class="Botones2Opciones">
            <a onclick="OcultarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a id="btnGuardarDatosLista" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
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
</div>