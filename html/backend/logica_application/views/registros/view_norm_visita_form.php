<script type="text/javascript">
    
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarVisita", "FormularioVisita");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioVisita", '../NormVisita/Guardar',
            'divContenidoGeneral', 'divErrorVisita');
    
    
    function PreguntaAccionVisita(mensaje, accion, valor="-1")
    {
        $("#pregunta_titulo_visita").html(mensaje);
        $("#pregunta_registro_visita").modal();
        
        $("#pregunta_registro_visita").val(accion);
        $("#accion_valor").val(valor);
    }
    
</script>

    <form id="FormularioVisita" method="post">
    
        <div style="position: fixed; width: <?php echo ($this->mfunciones_microcreditos->CheckIsMobile() ? '100' : '95'); ?>%; background-color: #fafafa; padding: 10px 0px; top: 58px; z-index: 1;">
            <div class="row"> 
                <div class="col" style="text-align: left;">
                    <span class="nav-avanza btn-danger" style="padding: 5px 18%;" onclick="OcultarPanelVisitas();"><i class="fa fa-ban" aria-hidden="true"></i> Cancelar </span>
                </div>

                <div class="col" style="text-align: right;">
                    <span class="nav-avanza" style="padding: 5px 18%;" onclick="PreguntaAccionVisita('<?php echo ($codigo_visita==-1 ? 'Guardar Nueva Visita' : 'Actualizar Visita #' . $codigo_contador); ?>. <br /><br /> ¿Seguro que quiere continuar?', 'nuevo_norm')"><i class="fa fa-floppy-o" aria-hidden="true"></i> GUARDAR </span>
                </div>
             </div>
        </div>
        
        <div style="clear: both;"></div>
        
        <br /><br />
        
        <div id="divErrorVisita" class="mensajeBD"> </div>
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading">REGISTRO VISITA <?php echo ($codigo_visita==-1 ? 'NUEVA' : '#' . $codigo_contador); ?></div>
            <div class="panel-body">
                
                <input type="hidden" name="norm_id" value="<?php if(isset($norm_id)){ echo $norm_id; } ?>" />
                <input type="hidden" name="codigo_visita" value="<?php if(isset($codigo_visita)){ echo $codigo_visita; } ?>" />
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='cv_resultado'><?php echo $this->lang->line('cv_resultado'); ?>:</label><?php echo $this->mfunciones_cobranzas->ObtenerCatalogoSelectNorm('cv_resultado', $arrRespuesta[0]['cv_resultado'], 'cobranzas_resultado_visita'); ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='cv_fecha_compromiso'><?php echo $this->lang->line('cv_fecha_compromiso'); ?>:</label> <span class="AyudaTooltip" data-balloon-length="small" data-balloon="<?php echo $this->lang->line('cv_fecha_compromiso_ayuda'); ?>" data-balloon-pos="top"></span><?php echo $arrCajasHTML['cv_fecha_compromiso']; ?></div></div>
                <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='cv_observaciones'><?php echo $this->lang->line('cv_observaciones'); ?> (Opcional Máx 280 car.):</label><?php echo $arrCajasHTML['cv_observaciones']; ?></div></div>
                
                <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='label-campo' for='cv_checkin'>
                            <?php 
                                
                                if($codigo_visita != -1)
                                {
                                    echo $this->lang->line('cv_checkin') . ': ' . ((int)$arrRespuesta[0]['cv_checkin']==0 ? '<i class="fa fa-exclamation-circle" aria-hidden="true"></i> No registrado' : '<i class="fa fa-check-square-o" aria-hidden="true"></i> Registrado');
                                    echo '<br /><br />';
                                }
                                
                                echo '<span style="font-size: 0.9em;"><i class="fa fa-info-circle" aria-hidden="true"></i> El <i>Check Visita</i> se marca desde la pestaña "RESUMEN" en la opción "CHECK VISITA". </span>';
                                
                                if(!$this->mfunciones_microcreditos->CheckIsMobile())
                                {
                                    echo '<br /><span style="font-size: 0.9em;"><i class="fa fa-info-circle" aria-hidden="true"></i> Desde el Backend puede utilizar la opción "Forzar Check Visita". </span>';
                                }
                            ?>
                        </label>
                    </div>
                </div>
                
            </div>
        </div>
        
        <div style="clear: both"></div>
        
    </form>
    
    
<div class="modal fade" id="pregunta_registro_visita" role="dialog">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="pregunta_titulo_visita">Seleccione para Continuar</h4>
        </div>
        <div class="modal-body">
            <div style="text-align: center;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 130px !important;">Cancelar</button>
                <button id="btnGuardarVisita" type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 150px !important;">Si, Continuar</button>
            </div>
        </div>
    </div>
</div>
</div>