<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<style>

    body {
        font-size: 7px;
    }

    table {
        font-size: 9px;
    }
    .square {
        font-size: 12px !important;
        margin: 0px;
        padding: 0px;
    }
    .aux_txt {
        color: #000000;
        font-style: italic;
    }
    @page {
        margin-left: 12mm;
        margin-right: 12mm;
        margin-top: 12mm;
        margin-bottom: 12mm;
    }
</style>

<p style="font-family: Tahoma,Verdana,Segoe,sans-serif;font-size:8px;margin-bottom:0px" >Formcred01</p>
<table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="1" cellpadding="0"
  cellspacing="0" bordercolor="#000000">

  <tr>
    <th style='border-bottom:none;font-size:11px' colspan="2"><u>SOLICITUD DE CRÉDITO</u></th>
  </tr>

  <tr>
    <th style='border-bottom:none;border-top:none;font-size:11px' colspan="2"><u>PERSONA NATURAL</u></th>
  </tr>

  <tr>
    <td style='border-right:none;border-bottom:none;border-top:none;text-align: center;font-size:8px;'>
        <b>Fecha de Solicitud:</b> &nbsp;&nbsp; <?php echo $arrRespuesta[0]['sol_fecha']; ?>
    </td>
    <td style='border-bottom:none;border-top:none;border-left: none;text-align: right;font-size:8px;'>
        <b>N° de Operación:</b> &nbsp;&nbsp; _______________________________
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </td>
  </tr>

  <tr>
    <td colspan="2" style='border-bottom:none;border-top:none;text-align: center;'> &nbsp; </td>
  </tr>

</table>

<?php

    $aux_datos_agencia = $this->mfunciones_microcreditos->ObtenerDatosRegionCodigo($arrRespuesta[0]['codigo_agencia_fie']);
        $aux_datos_agencia_lugar = ((int)$aux_datos_agencia->ciudad_codigo == 115 ? $aux_datos_agencia->ciudad : $aux_datos_agencia->departamento);
    
    $prop_table = 'style="line-height: 1; font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000"';
    
    $prop_celda = 'padding: 2.5px 0px;';
    
    $prop_celda_alto = 'padding: 3px 0px;';
    
    $img_solicitante = $this->config->base_url() . 'html_public/js/geo_img/images/block_left.jpg';
?>

<table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    
    <tr>
        <th colspan="2" style="<?php echo $prop_celda; ?> text-align: left;background-color:#d6dce4;font-size:10px">&nbsp;<u>I. DATOS DE (LOS) SOLICITANTE(S)</u></th>
    </tr>
    
    <tr>
        <td style="width:15px; background-color:#DBDBDB; border: 0px; border-bottom: 2px solid #000000; border-right: 1.5px solid #000000;">
            <img style="width:15px" src="<?php echo $img_solicitante; ?>">
        </td>
        
        <td valign="middle" style="border: 0px; border-bottom: 1.7px solid #000000;">
            
            <table <?php echo $prop_table; ?>>
                
                <tr>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 16%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;Apellidos y Nombres:&nbsp;&nbsp;
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 56%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        <span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_primer_apellido'] == '' ? ' _______________________________________________________________________________________' : mb_strtoupper(implode(' ', array($arrRespuesta[0]['sol_primer_apellido'], $arrRespuesta[0]['sol_segundo_apellido'], $arrRespuesta[0]['sol_primer_nombre'], $arrRespuesta[0]['sol_segundo_nombre'])))); ?></span>
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 28%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;&nbsp;C.I.:&nbsp;
                        <span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_ci'] == '' ? '______________________________________' : mb_strtoupper($arrRespuesta[0]["sol_ci"] . ' ' . $arrRespuesta[0]["sol_complemento"] . ' ' . ((int)$arrRespuesta[0]['sol_extension_codigo']==-1 ? '' : $arrRespuesta[0]['sol_extension_codigo'] . (str_replace(' ', '', $arrRespuesta[0]['sol_extension_codigo'])=='' ? '' : '.')))); ?></span>
                        &nbsp;
                    </td>
                    
                </tr>
                
            </table>
            
            <table <?php echo $prop_table; ?>>
                
                <tr>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 16%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;Fecha de Nacimiento:&nbsp;&nbsp;
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 11%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        <span class="aux_txt"><?php echo ((string)$arrRespuesta[0]['sol_fec_nac'] != '' ? $arrRespuesta[0]['sol_fec_nac'] : '_________________'); ?></span>
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 34%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        Estado Civil:&nbsp;&nbsp;<span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_est_civil_codigo']!='' && $arrRespuesta[0]['sol_est_civil_codigo']!='-1' ? $arrRespuesta[0]['sol_est_civil'] : '___________________________________________'); ?></span>
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 19%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;&nbsp;&nbsp;
                        Teléfono:
                        <span class="aux_txt"><?php echo ((string)$arrRespuesta[0]['sol_telefono'] != '' ? $arrRespuesta[0]['sol_telefono'] : '__________________'); ?></span>
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 20%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;&nbsp;&nbsp;Celular(es):
                        <span class="aux_txt"><?php echo ((string)$arrRespuesta[0]['sol_cel'] != '' ? $arrRespuesta[0]['sol_cel'] : '_________________'); ?></span>
                    </td>
                    
                </tr>
                
            </table>
            
            <table <?php echo $prop_table; ?>>
                
                <tr>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 25%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;NIT:
                        &nbsp;&nbsp;
                        <span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_nit'] != '' ? $arrRespuesta[0]['sol_nit'] : '______________ (si corresponde)'); ?></span> 
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 25%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;&nbsp;Cliente Antiguo:
                        &nbsp;&nbsp;&nbsp;<span class="square"><span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_cliente_codigo']==1 && $arrRespuesta[0]['sol_ci']!='' ? '&#9745;' : '&#9744;'); ?></span></span>
                        &nbsp;&nbsp;&nbsp;&nbsp;Nuevo:&nbsp;&nbsp;&nbsp;&nbsp;<span class="square"><span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_cliente_codigo']==0 && $arrRespuesta[0]['sol_ci']!='' ? '&#9745;' : '&#9744;'); ?></span></span>
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 50%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;&nbsp;&nbsp;Correo Electronico: <span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_correo'] != '' ? $arrRespuesta[0]['sol_correo'] : '____________________________________________________________'); ?></span>
                    </td>
                    
                </tr>
                
            </table>
            
        </td>
        
    </tr>
    
    <tr>
        <td style="width:15px; background-color:#DBDBDB; border: 0px; border-right: 1.5px solid #000000;">
            <img style="width:15px" src="<?php echo $img_solicitante; ?>">
        </td>
        
        <td valign="middle">
            
            <table <?php echo $prop_table; ?>>
                
                <tr>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 16%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;Apellidos y Nombres:&nbsp;&nbsp;
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 56%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        <span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_con_primer_apellido'] == '' ? ' _______________________________________________________________________________________' : mb_strtoupper(implode(' ', array($arrRespuesta[0]['sol_con_primer_apellido'], $arrRespuesta[0]['sol_con_segundo_apellido'], $arrRespuesta[0]['sol_con_primer_nombre'], $arrRespuesta[0]['sol_con_segundo_nombre'])))); ?></span>
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 28%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;&nbsp;C.I.:&nbsp;
                        <span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_con_ci'] == '' ? '______________________________________' : mb_strtoupper($arrRespuesta[0]["sol_con_ci"] . ' ' . $arrRespuesta[0]["sol_con_complemento"] . ' ' . ((int)$arrRespuesta[0]['sol_con_extension_codigo']==-1 ? '' : $arrRespuesta[0]['sol_con_extension_codigo'] . (str_replace(' ', '', $arrRespuesta[0]['sol_con_extension_codigo'])=='' ? '' : '.')))); ?></span>
                        &nbsp;
                    </td>
                    
                </tr>
                
            </table>
            
            <table <?php echo $prop_table; ?>>
                
                <tr>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 16%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;Fecha de Nacimiento:&nbsp;&nbsp;
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 11%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        <span class="aux_txt"><?php echo ((string)$arrRespuesta[0]['sol_con_fec_nac'] != '' ? $arrRespuesta[0]['sol_con_fec_nac'] : '_________________'); ?></span>
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 34%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        Estado Civil:&nbsp;&nbsp;<span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_con_est_civil_codigo']!='' && $arrRespuesta[0]['sol_con_est_civil_codigo']!='-1' ? $arrRespuesta[0]['sol_con_est_civil'] : '___________________________________________'); ?></span>
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 19%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;&nbsp;&nbsp;
                        Teléfono:
                        <span class="aux_txt"><?php echo ((string)$arrRespuesta[0]['sol_con_telefono'] != '' ? $arrRespuesta[0]['sol_con_telefono'] : '__________________'); ?></span>
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 20%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;&nbsp;&nbsp;Celular(es):
                        <span class="aux_txt"><?php echo ((string)$arrRespuesta[0]['sol_con_cel'] != '' ? $arrRespuesta[0]['sol_con_cel'] : '_________________'); ?></span>
                    </td>
                    
                </tr>
                
            </table>
            
            <table <?php echo $prop_table; ?>>
                
                <tr>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 25%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;NIT:
                        &nbsp;&nbsp;
                        <span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_con_nit'] != '' ? $arrRespuesta[0]['sol_con_nit'] : '______________ (si corresponde)'); ?></span> 
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 25%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;&nbsp;Cliente Antiguo:
                        &nbsp;&nbsp;&nbsp;<span class="square"><span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_cliente_codigo']==1 && $arrRespuesta[0]['sol_con_ci']!='' ? '&#9745;' : '&#9744;'); ?></span></span>
                        &nbsp;&nbsp;&nbsp;&nbsp;Nuevo:&nbsp;&nbsp;&nbsp;&nbsp;<span class="square"><span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_cliente_codigo']==0 && $arrRespuesta[0]['sol_con_ci']!='' ? '&#9745;' : '&#9744;'); ?></span></span>
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 50%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;&nbsp;&nbsp;Correo Electronico: <span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_con_correo'] != '' ? $arrRespuesta[0]['sol_con_correo'] : '____________________________________________________________'); ?></span>
                    </td>
                    
                </tr>
                
            </table>
            
        </td>
        
    </tr>
    
</table>


<table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    
    <tr>
        <th colspan="2" style="<?php echo $prop_celda; ?> text-align: left;background-color:#d6dce4;font-size:10px">&nbsp;<u>II. DATOS DE LA(S) ACTIVIDAD(ES) ECONOMICA(S)</u></th>
    </tr>
    
    <tr>
        <td style="width:15px; background-color:#DBDBDB; border: 0px; border-right: 1.5px solid #000000;">
            <img style="width:15px;" src="<?php echo $img_solicitante; ?>">
        </td> 
   
    
        <td valign="top" style="border: 0px;">
            
            <table <?php echo $prop_table; ?> style="width:684px;">
                
                <tr>
                    
                    <td valign="top" style='margin: 0px; padding: 0px; width: 48%; border-right:2px solid #000000;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        
                        <table <?php echo $prop_table; ?>>
                        
                            <tr>
                                
                                <th colspan="2" style="<?php echo $prop_celda; ?> background-color:#DBDBDB; border: 0px; border-bottom: 1.7px solid #000000;">INDEPENDIENTE</th>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 40%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    &nbsp;Actividad que realiza:&nbsp;
                                
                                </td>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 60%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_dependencia']==2 && $arrRespuesta[0]['sol_indepen_actividad']!='' ? $arrRespuesta[0]['sol_indepen_actividad'] : '______________________________________________'); ?></span>
                                
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 40%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    &nbsp;Antigüedad en la actividad:&nbsp;
                                
                                </td>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 60%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_dependencia']==2 && ((int)$arrRespuesta[0]['sol_indepen_ant_ano']+(int)$arrRespuesta[0]['sol_indepen_ant_mes']>0) ? $arrRespuesta[0]['sol_indepen_ant_ano'] . ' año(s) y ' . $arrRespuesta[0]['sol_indepen_ant_mes'] . ' mes(es)' : '______________________________________________'); ?></span>
                                
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 40%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    &nbsp;Horario y días de atención:&nbsp;
                                
                                </td>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 60%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    <span class="aux_txt">
                                    <?php
                                        $aux_horario = $this->mfunciones_microcreditos->getHoursDaysAttention($arrRespuesta[0]['sol_indepen_horario_desde'], $arrRespuesta[0]['sol_indepen_horario_hasta'], $arrRespuesta[0]['sol_indepen_horario_dias']);
                                        echo ((int)$arrRespuesta[0]['sol_dependencia']==2 && $aux_horario!='' ? $aux_horario : '______________________________________________');
                                    ?>
                                    </span>
                                
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 40%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    &nbsp;Teléfono de la actividad:&nbsp;
                                
                                </td>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 60%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_dependencia']==2 && $arrRespuesta[0]['sol_indepen_telefono']!='' ? $arrRespuesta[0]['sol_indepen_telefono'] : '______________________________________________'); ?></span>
                                
                                </td>
                                
                            </tr>
                        
                        </table>
                        
                    </td>
                    
                    <td valign="top" style='margin: 0px; padding: 0px; width: 52%; border-right:none;border-bottom:none;border-left:1px solid #000000;border-top:none;text-align: left;'>
                        
                        <table <?php echo $prop_table; ?>>
                        
                            <tr>
                                
                                <th style="<?php echo $prop_celda; ?> background-color:#DBDBDB; border: 0px; border-bottom: 1.7px solid #000000;">DEPENDIENTE</th>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="top" style='margin: 0px; padding: 0px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                    <table <?php echo $prop_table; ?>>

                                        <tr>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 35%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                &nbsp;Nombre de la empresa:&nbsp;

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 65%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_dependencia']==1 && $arrRespuesta[0]['sol_depen_empresa']!='' ? $arrRespuesta[0]['sol_depen_empresa'] : '______________________________________________________'); ?></span>

                                            </td>

                                        </tr>

                                    </table>
                                    
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="top" style='margin: 0px; padding: 0px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                    <table <?php echo $prop_table; ?>>

                                        <tr>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 35%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                &nbsp;Actividad de la empresa:&nbsp;

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 65%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_dependencia']==1 && $arrRespuesta[0]['sol_depen_actividad']!='' ? $arrRespuesta[0]['sol_depen_actividad'] : '______________________________________________________'); ?></span>

                                            </td>

                                        </tr>

                                    </table>
                                    
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="top" style='margin: 0px; padding: 0px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                    <table <?php echo $prop_table; ?>>

                                        <tr>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 20%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                &nbsp;Cargo actual:&nbsp;

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 40%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_dependencia']==1 && $arrRespuesta[0]['sol_depen_cargo']!='' ? $arrRespuesta[0]['sol_depen_cargo'] : '______________________________'); ?></span>

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 20%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                &nbsp;Antigüedad:&nbsp;

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 20%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_dependencia']==1 && ((int)$arrRespuesta[0]['sol_depen_ant_ano']+(int)$arrRespuesta[0]['sol_depen_ant_mes']>0) ? $arrRespuesta[0]['sol_depen_ant_ano'] . ' año(s) y ' . $arrRespuesta[0]['sol_depen_ant_mes'] . ' mes(es)' : '______________'); ?></span>

                                            </td>

                                        </tr>

                                    </table>
                                    
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="top" style='margin: 0px; padding: 0px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                    <table <?php echo $prop_table; ?>>

                                        <tr>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 35%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                &nbsp;Horario y días de trabajo:&nbsp;

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 65%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                <span class="aux_txt">
                                                <?php
                                                    $aux_horario = $this->mfunciones_microcreditos->getHoursDaysAttention($arrRespuesta[0]['sol_depen_horario_desde'], $arrRespuesta[0]['sol_depen_horario_hasta'], $arrRespuesta[0]['sol_depen_horario_dias']);
                                                    echo ((int)$arrRespuesta[0]['sol_dependencia']==1 && $aux_horario!='' ? $aux_horario : '______________________________________________________');
                                                ?>
                                                </span>

                                            </td>

                                        </tr>

                                    </table>
                                    
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="top" style='margin: 0px; padding: 0px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                    <table <?php echo $prop_table; ?>>

                                        <tr>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 25%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                &nbsp;Tipo de contrato:&nbsp;

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 30%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_dependencia']==1 && $arrRespuesta[0]['sol_depen_tipo_contrato']!='' ? $arrRespuesta[0]['sol_depen_tipo_contrato'] : '_______________________'); ?></span>

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 20%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                &nbsp;Teléfono:&nbsp;

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 25%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_dependencia']==1 && $arrRespuesta[0]['sol_depen_telefono']!='' ? $arrRespuesta[0]['sol_depen_telefono'] : '___________________'); ?></span>

                                            </td>

                                        </tr>

                                    </table>
                                    
                                </td>
                                
                            </tr>
                            
                        </table>
                        
                    </td>
                    
                </tr>
                
            </table>
        
        </td>
            
    </tr>
    
    <tr>
        <td style="width:15px; background-color:#DBDBDB; border: 0px; border-top: 2px solid #000000; border-right: 1.5px solid #000000;">
            <img style="width:15px;" src="<?php echo $img_solicitante; ?>">
        </td>
    
    
        <td valign="top" style="border: 0px;">
            
            <table <?php echo $prop_table; ?> style="width:684px;">
                
                <tr>
                    
                    <td valign="top" style='margin: 0px; padding: 0px; width: 48%; border-right:2px solid #000000;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        
                        <table <?php echo $prop_table; ?>>
                        
                            <tr>
                                
                                <th colspan="2" style="<?php echo $prop_celda; ?> background-color:#DBDBDB; border: 0px; border-bottom: 1.7px solid #000000; border-top: 1.7px solid #000000;">INDEPENDIENTE</th>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 40%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    &nbsp;Actividad que realiza:&nbsp;
                                
                                </td>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 60%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_dependencia']==2 && $arrRespuesta[0]['sol_con_indepen_actividad']!='' ? $arrRespuesta[0]['sol_con_indepen_actividad'] : '______________________________________________'); ?></span>
                                
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 40%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    &nbsp;Antigüedad en la actividad:&nbsp;
                                
                                </td>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 60%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_dependencia']==2 && ((int)$arrRespuesta[0]['sol_con_indepen_ant_ano']+(int)$arrRespuesta[0]['sol_con_indepen_ant_mes']>0) ? $arrRespuesta[0]['sol_con_indepen_ant_ano'] . ' año(s) y ' . $arrRespuesta[0]['sol_con_indepen_ant_mes'] . ' mes(es)' : '______________________________________________'); ?></span>
                                
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 40%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    &nbsp;Horario y días de atención:&nbsp;
                                
                                </td>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 60%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    <span class="aux_txt">
                                    <?php
                                        $aux_horario = $this->mfunciones_microcreditos->getHoursDaysAttention($arrRespuesta[0]['sol_con_indepen_horario_desde'], $arrRespuesta[0]['sol_con_indepen_horario_hasta'], $arrRespuesta[0]['sol_con_indepen_horario_dias']);
                                        echo ((int)$arrRespuesta[0]['sol_con_dependencia']==2 && $aux_horario!='' ? $aux_horario : '______________________________________________');
                                    ?>
                                    </span>
                                
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 40%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    &nbsp;Teléfono de la actividad:&nbsp;
                                
                                </td>
                                
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 60%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                
                                    <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_dependencia']==2 && $arrRespuesta[0]['sol_con_indepen_telefono']!='' ? $arrRespuesta[0]['sol_con_indepen_telefono'] : '______________________________________________'); ?></span>
                                
                                </td>
                                
                            </tr>
                        
                        </table>
                        
                    </td>
                    
                    <td valign="top" style='margin: 0px; padding: 0px; width: 52%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        
                        <table <?php echo $prop_table; ?>>
                        
                            <tr>
                                
                                <th style="<?php echo $prop_celda; ?> background-color:#DBDBDB; border: 0px; border-bottom: 1.7px solid #000000; border-top: 1.7px solid #000000;">DEPENDIENTE</th>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="top" style='margin: 0px; padding: 0px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                    <table <?php echo $prop_table; ?>>

                                        <tr>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 35%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                &nbsp;Nombre de la empresa:&nbsp;

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 65%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_dependencia']==1 && $arrRespuesta[0]['sol_con_depen_empresa']!='' ? $arrRespuesta[0]['sol_con_depen_empresa'] : '______________________________________________________'); ?></span>

                                            </td>

                                        </tr>

                                    </table>
                                    
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="top" style='margin: 0px; padding: 0px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                    <table <?php echo $prop_table; ?>>

                                        <tr>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 35%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                &nbsp;Actividad de la empresa:&nbsp;

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 65%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_dependencia']==1 && $arrRespuesta[0]['sol_con_depen_actividad']!='' ? $arrRespuesta[0]['sol_con_depen_actividad'] : '______________________________________________________'); ?></span>

                                            </td>

                                        </tr>

                                    </table>
                                    
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="top" style='margin: 0px; padding: 0px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                    <table <?php echo $prop_table; ?>>

                                        <tr>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 20%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                &nbsp;Cargo actual:&nbsp;

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 40%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_dependencia']==1 && $arrRespuesta[0]['sol_con_depen_cargo']!='' ? $arrRespuesta[0]['sol_con_depen_cargo'] : '______________________________'); ?></span>

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 20%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                &nbsp;Antigüedad:&nbsp;

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 20%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_dependencia']==1 && ((int)$arrRespuesta[0]['sol_con_depen_ant_ano']+(int)$arrRespuesta[0]['sol_con_depen_ant_mes']>0) ? $arrRespuesta[0]['sol_con_depen_ant_ano'] . ' año(s) y ' . $arrRespuesta[0]['sol_con_depen_ant_mes'] . ' mes(es)' : '______________'); ?></span>

                                            </td>

                                        </tr>

                                    </table>
                                    
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="top" style='margin: 0px; padding: 0px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                    <table <?php echo $prop_table; ?>>

                                        <tr>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 35%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                &nbsp;Horario y días de trabajo:&nbsp;

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 65%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                <span class="aux_txt">
                                                <?php
                                                    $aux_horario = $this->mfunciones_microcreditos->getHoursDaysAttention($arrRespuesta[0]['sol_con_depen_horario_desde'], $arrRespuesta[0]['sol_con_depen_horario_hasta'], $arrRespuesta[0]['sol_con_depen_horario_dias']);
                                                    echo ((int)$arrRespuesta[0]['sol_con_dependencia']==1 && $aux_horario!='' ? $aux_horario : '______________________________________________________');
                                                ?>
                                                </span>

                                            </td>

                                        </tr>

                                    </table>
                                    
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <td valign="top" style='margin: 0px; padding: 0px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                    <table <?php echo $prop_table; ?>>

                                        <tr>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 25%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                &nbsp;Tipo de contrato:&nbsp;

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 30%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_dependencia']==1 && $arrRespuesta[0]['sol_con_depen_tipo_contrato']!='' ? $arrRespuesta[0]['sol_con_depen_tipo_contrato'] : '_______________________'); ?></span>

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 20%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                &nbsp;Teléfono:&nbsp;

                                            </td>

                                            <td valign="middle" style='<?php echo $prop_celda; ?> width: 25%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

                                                <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_dependencia']==1 && $arrRespuesta[0]['sol_con_depen_telefono']!='' ? $arrRespuesta[0]['sol_con_depen_telefono'] : '___________________'); ?></span>

                                            </td>

                                        </tr>

                                    </table>
                                    
                                </td>
                                
                            </tr>
                            
                        </table>
                        
                    </td>
                    
                </tr>
                
            </table>
        
        </td>
            
    </tr>
    
    <tr>
        
        <td colspan="2" style="<?php echo $prop_celda; ?> border: 0px; border-right:none;border-bottom:none;border-left:none;border-top: 2px solid #000000;">
            &nbsp; ¿Adicionalmente a lo declarado, su unidad familiar cuenta con otra (s) actividad (es) económica (s)?
            &nbsp;&nbsp; SI <span class="square">&#9744;</span>
            &nbsp;&nbsp; NO <span class="square">&#9744;</span>
            
            &nbsp;&nbsp;&nbsp; Descripción: _____________________________________
            
        </td>
        
    </tr>
    
</table>

<table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="1" cellpadding="0"
  cellspacing="0" bordercolor="#000000">

  <tr>

    <th colspan="12" style="<?php echo $prop_celda_alto; ?> text-align: left;font-size:10px;background-color:#d6dce4;">&nbsp;<u>III. DATOS DEL CRÉDITO</u></th>

  </tr>

  <tr>

    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-left:none;border-bottom:none;border-top:none;text-align: left;' colspan="12">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CRÉDITO DIRECTO<span class="square">&nbsp;&#9744;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LÍNEA DE CRÉDITO<span class="square">&nbsp;&#9744;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REFINANCIAMIENTO<span class="square">&nbsp;&#9744;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CRÉDITO BAJO LÍNEA<span class="square">&nbsp;&#9744;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EXPROMISIÓN<span class="square">&nbsp;&#9744;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REPROGRAMACIÓN*<span class="square">&nbsp;&#9744;</span>&nbsp;</td>

  </tr>

  <tr>

    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;' colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MONTO SOLICITADO</td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;' colspan="3">Bs. &nbsp;<span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_monto']>0 && $arrRespuesta[0]['sol_moneda_codigo']=='bob' ? number_format($arrRespuesta[0]['sol_monto'], 2, ',', '.') : '_____________________&nbsp;&nbsp;'); ?></span></td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;' colspan="3">$us.&nbsp;<span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_monto']>0 && $arrRespuesta[0]['sol_moneda_codigo']=='usd' ? number_format($arrRespuesta[0]['sol_monto'], 2, ',', '.') : '_____________________&nbsp;&nbsp;'); ?></span></td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;' colspan="3">FECHA DE PAGO:&nbsp;__________________________________&nbsp;</td>

  </tr>

  <tr>

    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;' colspan="4">
    &nbsp;*&nbsp;&nbsp;&nbsp;FRECUENCIA DE PAGO SOLICITADA</td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>Mensual</td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'><span class="square">&#9744;</span></td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>Trimestral</td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'><span class="square">&#9744;</span></td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>Semestral</td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'><span class="square">&#9744;</span></td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>Otro:</td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>_______________________</td>

  </tr>
  <tr>

    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-left:none;border-bottom:none;border-top:none;text-align: left;' colspan="3">&nbsp;*&nbsp;&nbsp;&nbsp;PROPUESTA&nbsp;DE&nbsp;CUOTA&nbsp;A&nbsp;PAGAR</td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: center;' colspan="3"> Bs &nbsp;&nbsp;___________________</td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left: none;text-align: center;' colspan="3"> $us &nbsp;&nbsp;___________________</td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left: none;text-align: left;' colspan="3">&nbsp;* PLAZO &nbsp;<span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_plazo']>0 ? $arrRespuesta[0]['sol_plazo'] . ' mes(es)' : '&nbsp;&nbsp;________________________________________'); ?></span></td>

  </tr>

  <tr>

    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;' colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DETALLE DEL DESTINO DEL CRÉDITO SOLICITADO</td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left; padding-right: 5px; text-align: justify; line-height: 1;' colspan="7"><span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_monto']>0 ? $arrRespuesta[0]['sol_detalle'] : '&nbsp;___________________________________________________________________________________________________________&nbsp;'); ?></span></td>

  </tr>
  <tr >
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left; font-style: italic; font-size: 8px;' colspan="12">&nbsp;&nbsp;* Para solicitud de reprogramación solo es necesario llenar los puntos marcados con asterisco (*)</td>
  </tr>
  <tr>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;' colspan="12">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EN CASO DE REALIZAR PAGOS ADELANTADOS A CAPITAL UD. DESEA: </td>
  </tr>
  <tr>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: center;' colspan="6">REDUCIR EL MONTO DE LAS CUOTAS &nbsp;&nbsp; <span class="square">&nbsp;&#9744;</span></td>
    <td style='<?php echo $prop_celda_alto; ?> border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: center;' colspan="6">REDUCIR EL PLAZO DEL CRÉDITO &nbsp;&nbsp; <span class="square">&nbsp;&#9744;</span></td>
  </tr>
  <tr>
      <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left; font-style: italic; font-size: 8px; padding-bottom: 5px;' colspan="12">&nbsp;&nbsp;Nota: La elección de una de estas alternativas no impide que el prestatario pueda elegir posteriormente otra.</td>
  </tr>
</table>

<table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="1" cellpadding="0"
  cellspacing="0" bordercolor="#000000">

  <tr>

    <th colspan="2" style="<?php echo $prop_celda; ?> text-align: left;background-color:#d6dce4; font-size: 10px;">&nbsp;<u>IV. DÉBITO AUTOMÁTICO</u></th>

  </tr>

  <tr>  
    <td style="border-top:none;border-left:none;border-right:none;border-bottom:none;border-right:none">
      <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;"rules=none width="100%" border="0" cellpadding="0" cellspacing="0" >
        <tr>
          <td style='border-right:none;border-bottom:none;border-top:none;text-align: left;'>&nbsp;<u>¿Cuenta con Caja de Ahorro/Cuenta Corriente en Banco FIE?</u></td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left: none;text-align: center;'>
            SI</td>
          <td style='border-left: none;border-right:none;border-bottom:none;border-top:none;text-align: center;'><span class="square">&#9744;</span></td>
          <td style='border-left: none;border-right:none;border-bottom:none;border-top:none;text-align: center;'>NO</td>
          <td style='border-left: none;border-right:none;border-bottom:none;border-top:none;text-align: center;'><span class="square">&#9744;</span></td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
        </tr>
        <tr>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
        </tr>
        <tr>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;<u>
          ¿Desea realizar el pago de su crédito mediante debito automatico?</u></td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: center;'>SI</td>
          <td style='border-left: none;border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: center;'>&nbsp;&nbsp;<span class="square">&#9744;</span>*&nbsp;&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: center;'>NO</td>
          <td style='border-left: none;border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: center;'>&nbsp;&nbsp;<span class="square">&#9744;</span>&nbsp;&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
        </tr>
        <tr>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
        </tr>
        <tr>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
          <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left;'>&nbsp;</td>
        </tr>
      </table>
    </td>
    <td align="left" style="border-top:none;border-left:none;border-right:none;border-bottom:none;">
        <br />
      <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="90%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
          <tr>
            <td style='text-align: center;'><u>NÚMERO DE CUENTA</u></td>
            <td style='text-align: center;'><u>TIPO DE CUENTA</u></td>
            <td style='text-align: center;'>DEBITO **</td>
          </tr>
          <tr>
            <td style='text-align: left;'>&nbsp;</td>
            <td style='text-align: left;'>&nbsp;</td>
            <td style='text-align: left;'>&nbsp;</td>
          </tr>
          <tr>
            <td style='text-align: left;'>&nbsp;</td>
            <td style='text-align: left;'>&nbsp;</td>
            <td style='text-align: left;'>&nbsp;</td>
          </tr>
          <tr>
            <td style='text-align: left;'>&nbsp;</td>
            <td style='text-align: left;'>&nbsp;</td>
            <td style='text-align: left;'>&nbsp;</td>
          </tr>
          <tr>
            <td style='text-align: left;'>&nbsp;</td>
            <td style='text-align: left;'>&nbsp;</td>
            <td style='text-align: left;'>&nbsp;</td>
          </tr>
      </table>
    </td>

  </tr>
  <tr>
    <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left; padding-left: 12px; padding-top: 5px; padding-right: 5px; font-style: italic; font-size: 8px;' colspan="2">* En caso de ser aprobada  la presente solicitud de credito autorizo(amos) expresamente a Banco FIE S.A. realizar la vinculación de las cuentas de ahorro/cuentas corrientes declaradas con el fin de contar con el débito automático para el pago de mi (nuestro) préstamo solicitado.</td>}
  </tr>
  <tr>
    <td style='border-right:none;border-bottom:none;border-top:none;border-left:none;text-align: left; padding-left: 5px; font-style: italic; font-size: 8px; padding-top: 5px;' colspan="2">&nbsp;** Marcar con una X la(s) cuenta(s) donde se aplicara el débito automático. </td>
  </tr>
</table>

<table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <th colspan="12" style="<?php echo $prop_celda; ?> text-align: left;background-color:#d6dce4; font-size: 10px;">&nbsp;<u>V. RELACIONES Y VINCULACIONES </u></th>
  </tr> 
  <tr>
    <td style='<?php echo $prop_celda; ?> border-right:none;border-bottom:none;border-top:none;text-align: left;' colspan="12">
        
        &nbsp;1. Tiene relación de parentesco con algún (a) trabajador (a) de Banco FIE?
        &nbsp;&nbsp;&nbsp;
        &nbsp;SI <span class="square">&nbsp;&#9744;</span>
        &nbsp;NO <span class="square">&nbsp;&#9744;</span>
        &nbsp;&nbsp;&nbsp; Nombre: ___________________________ &nbsp;&nbsp; Parentesco: _______________________
        
    </td>
  </tr>
  <tr>
    <td style='<?php echo $prop_celda; ?> border-right:none;border-bottom:none;border-top:none;text-align: left;' colspan="12">
        
        &nbsp;2. Tiene relación de parentesco con algún prestatario de Banco FIE?
        &nbsp;&nbsp;&nbsp;
        &nbsp;SI <span class="square">&nbsp;&#9744;</span>
        &nbsp;NO <span class="square">&nbsp;&#9744;</span>
        &nbsp;&nbsp;&nbsp; Nombre: ___________________________ &nbsp;&nbsp; Parentesco: ___________
        
        &nbsp; Operación*: ____
        
    </td>
  </tr>
  <tr>
    <td style='<?php echo $prop_celda; ?> border-right:none;border-bottom:none;border-top:none;text-align: left;' colspan="12">
        
        &nbsp;3. Tiene algun tipo de vinculación con persona (as) natural (es) o jurídica (s)
        &nbsp;&nbsp;&nbsp;
        &nbsp;SI <span class="square">&nbsp;&#9744;</span>
        &nbsp;NO <span class="square">&nbsp;&#9744;</span>
        
    </td>
  </tr>
  
  <tr>
    <td style='<?php echo $prop_celda; ?> border-right:none;border-bottom:none;border-top:none;text-align: left;' colspan="12">&nbsp;Indique el nombre de las personas naturales y/o juridicas con las cuales se encuentra vinculado. Identificando a cada persona según tipo de relación:</td>
  </tr>
  
  <tr>
    <td style='<?php echo $prop_celda; ?> border-right:none;border-bottom:none;border-top:none;text-align: left;' colspan="12">
    
        <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;border-top:0px" rules=none width="100%"  cellpadding="0" border="0" cellspacing="0" bordercolor="#000000">
            <tr>
              <td valign="top" style="width:50%;border:none">
                <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;"rules=none width="100%" border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td style='<?php echo $prop_celda; ?> border:none;text-align: left;'>&nbsp;&nbsp;&nbsp;a) Relaciones por Propiedad: ____________________________________________________</td>
                  </tr>
                  <tr>
                    <td style='<?php echo $prop_celda; ?> border:none;text-align: left;'>&nbsp;&nbsp;&nbsp;b) Relaciones por Administración: _______________________________________________</td>
                  </tr>
                  <tr>
                    <td style='<?php echo $prop_celda; ?> border:none;text-align: left;'>&nbsp;&nbsp;&nbsp;e) Soy garante Personal o Hipotecario  en Banco FIE de: __________________________</td>
                  </tr>
                  <tr>
                    <td style='<?php echo $prop_celda; ?> border:none;text-align: left;'>&nbsp;&nbsp;&nbsp;__________________________________________________________________________________</td>
                  </tr>
                  <tr>
                      <td valign="bottom" style='<?php echo $prop_celda; ?> border:none;text-align: left; font-style: italic; height: 20px;'>&nbsp;* Llenado exclusivo por BANCO FIE S.A.</td>
                  </tr>
                </table>
              </td>
              <td valign="top" style="width:50%">
                <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;"rules=none width="100%" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td style='<?php echo $prop_celda; ?> border:none;text-align: left;'>&nbsp;&nbsp;&nbsp;c) Relaciones por Actividad: _____________________________________________________</td>
                  </tr>
                  <tr>
                    <td style='<?php echo $prop_celda; ?> border:none;text-align: left;'>&nbsp;&nbsp;&nbsp;d) Relaciones por Destino: ______________________________________________________</td>
                  </tr>
                  <tr>
                    <td style='<?php echo $prop_celda; ?> border:none;text-align: left;'>&nbsp;&nbsp;&nbsp;f) Actualmente me garantizan en Banco FIE: _____________________________________</td>
                  </tr>
                  <tr>
                    <td style='<?php echo $prop_celda; ?> border:none;text-align: left;'>&nbsp;&nbsp;&nbsp;__________________________________________________________________________________</td>
                  </tr>
                </table>
              </td>
            </tr>
        </table>
    
    </td>
  </tr>
  
</table>
        
<table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <th colspan="7" style="<?php echo $prop_celda; ?> text-align: left;background-color:#d6dce4; font-size: 10px;">&nbsp;<u>VI. SEGURO DESGRAVAMEN</u></th>
  </tr> 
  <tr>
    <td style='<?php echo $prop_celda; ?> border-right:none;border-bottom:none;border-top:none;text-align: left;'>&nbsp;¿Desea realizar sus pagos al seguro de desgravamen como parte de las cuotas correspondientes al pago del crédito?</td>
    <td style='<?php echo $prop_celda; ?> border-left:none;border-right:none;border-bottom:none;border-top:none;text-align: left;'>SI</td>
    <td style='<?php echo $prop_celda; ?> border-left:none;border-right:none;border-bottom:none;border-top:none;text-align: left;'>&nbsp;<span class="square">&#9744;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td style='<?php echo $prop_celda; ?> border-left:none;border-right:none;border-bottom:none;border-top:none;text-align: left;'>NO</td>
    <td style='<?php echo $prop_celda; ?> border-left:none;border-right:none;border-bottom:none;border-top:none;text-align: left;'>&nbsp;<span class="square">&#9744;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td style='<?php echo $prop_celda; ?> border-left:none;border-right:none;border-bottom:none;border-top:none;text-align: left;'>N/A</td>
    <td style='<?php echo $prop_celda; ?> border-left:none;border-bottom:none;border-top:none;text-align: left;'>&nbsp;<span class="square">&#9744;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr> 
  
</table>

<table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <th colspan="4" style="<?php echo $prop_celda; ?> text-align: left;background-color:#d6dce4; font-size: 10px;">&nbsp;<u>VII. REFERENCIAS PERSONALES</u></th>
  </tr> 
  <tr >
    <th style="<?php echo $prop_celda; ?> text-align: center; width:25%;background-color:#DBDBDB;">APELLIDOS Y NOMBRES</th>
    <th style="<?php echo $prop_celda; ?> text-align: center; width:25%;background-color:#DBDBDB;">TIPO DE RELACIÓN</th>
    <th style="<?php echo $prop_celda; ?> text-align: center; width:25%;background-color:#DBDBDB;">DIRECCIÓN</th>
    <th style="<?php echo $prop_celda; ?> text-align: center; width:25%;background-color:#DBDBDB;">TELÉFONO(S)</th>
  </tr>
  <tr>
    <td style="<?php echo $prop_celda; ?> text-align: center;">&nbsp;</td>
    <td style="<?php echo $prop_celda; ?> text-align: center;">&nbsp;</td>
    <td style="<?php echo $prop_celda; ?> text-align: center;">&nbsp;</td>
    <td style="<?php echo $prop_celda; ?> text-align: center;">&nbsp;</td>
  </tr>
  <tr>
    <td style="<?php echo $prop_celda; ?> text-align: center;">&nbsp;</td>
    <td style="<?php echo $prop_celda; ?> text-align: center;">&nbsp;</td>
    <td style="<?php echo $prop_celda; ?> text-align: center;">&nbsp;</td>
    <td style="<?php echo $prop_celda; ?> text-align: center;">&nbsp;</td>
  </tr>
</table>

<table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    
    <tr>
        <th style="<?php echo $prop_celda; ?> text-align: left;background-color:#d6dce4; font-size: 10px;">&nbsp;<u>VIII. CROQUIS</u></th>
    </tr>
    
    <tr>
        <td valign="middle" style='<?php echo $prop_celda; ?> text-align: center; background-color:#d6dce4; border-right:none;border-bottom: 1.7px solid #000000;border-left:none;border-top:none;'>

            <b>DOMICILIO</b>

        </td>
    </tr>
    
    <tr>
        <td valign="top" style='<?php echo $prop_celda; ?> border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

            <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">

                <tr>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 30%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;Dirección de la casa donde vive:&nbsp;&nbsp;Calle/Avenida:
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 39%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        <span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_direccion_dom']!='' ? implode(' ', array($arrRespuesta[0]['sol_direccion_dom'], $arrRespuesta[0]['sol_edificio_dom'])) : '___________________________________________________________'); ?></span>
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 10%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;N° &nbsp; <span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_numero_dom']!='' ? $arrRespuesta[0]['sol_numero_dom'] : '__________'); ?></span>
                    </td>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 21%; padding-right: 5px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: right;'>
                        &nbsp;Zona: &nbsp; <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_cod_barrio_dom_codigo']>0 ? $arrRespuesta[0]['sol_cod_barrio_dom'] : '_____________________'); ?></span>
                    </td>
                    
                </tr>
                
            </table>

        </td>
        
    </tr>
    <tr>
        
        <td valign="top" style='<?php echo $prop_celda; ?> border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
            
            <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">

                <tr>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 60%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        &nbsp;La casa donde vive es:
                        
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        
                        Propia &nbsp; <span class="square"><span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_dom_tipo']==1 && $arrRespuesta[0]['sol_ci']!='' ? '&#9745;' : '&#9744;'); ?></span></span>
                        
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        
                        En Alquiler &nbsp; <span class="square"><span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_dom_tipo']==2 && $arrRespuesta[0]['sol_ci']!='' ? '&#9745;' : '&#9744;'); ?></span></span>
                        
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        
                        En Anticrético &nbsp; <span class="square"><span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_dom_tipo']==3 && $arrRespuesta[0]['sol_ci']!='' ? '&#9745;' : '&#9744;'); ?></span></span>
                        
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        
                        Familiar &nbsp; <span class="square"><span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_dom_tipo']==4 && $arrRespuesta[0]['sol_ci']!='' ? '&#9745;' : '&#9744;'); ?></span></span>
                        
                    </td>
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 10%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: right;'>
                        &nbsp;Otro:&nbsp;
                    </td>
                    <td valign="middle" style='<?php echo $prop_celda; ?> width: 30%; padding-right: 5px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_dom_tipo']==99 && $arrRespuesta[0]['sol_ci']!='' ? $arrRespuesta[0]['sol_dom_tipo_otro'] : '___________________________________________________'); ?></span>
                    </td>
                    
                </tr>
                
            </table>
            
        </td>
        
    </tr>
    
    <tr>
        
        <td valign="middle" style='<?php echo $prop_celda; ?> height: 271px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: center;'>
        
            <?php

                if($arrRespuesta[0]['sol_ci'] != '')
                {
                    switch ((int)$arrRespuesta[0]['sol_dir_referencia_dom']) {
                        case 1:
                            // GEO
                            $img_aux = $arrRespuesta[0]['sol_geolocalizacion_dom_img'];
                            break;

                        case 2:
                            // Croquis
                            $img_aux = $this->mfunciones_microcreditos->ConvertToJPEG($arrRespuesta[0]['sol_croquis_dom']);
                            break;

                        default:
                            $img_aux = '';
                            break;
                    }

                    if($this->mfunciones_microcreditos->validateIMG_simple($img_aux))
                    {
                        echo '<img src="' . $img_aux . '" style="height: 271px; max-width: 95%;" />';
                    }
                }

            ?>
            
        </td>
        
    </tr>
    
</table>

<table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    
    <tr>
        <td colspan="2" valign="middle" style='<?php echo $prop_celda; ?> text-align: center; background-color:#d6dce4; border-right:none;border-bottom: 1.7px solid #000000;border-left:none;border-top:none;'>

            <b>ACTIVIDAD(ES)</b>

        </td>
    </tr>
    
    <tr>
        
        <td valign="top" style='<?php echo $prop_celda; ?> width: 50%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>

            <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">

                <tr>
                    
                    <td valign="top" style='border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        
                        <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">

                            <tr>

                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 54%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    &nbsp;Dirección de la actividad:&nbsp;&nbsp;Calle/Avenida:
                                </td>

                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 46%; padding-right: 5px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    <span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_direccion_trabajo']!='' ? implode(' ', array($arrRespuesta[0]['sol_direccion_trabajo'], $arrRespuesta[0]['sol_edificio_trabajo'])) : '______________________________________'); ?></span>
                                </td>

                            </tr>

                        </table>
                        
                    </td>
                    
                </tr>
                
                <tr>
                    
                    <td valign="top" style='border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        
                        <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">

                            <tr>

                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 30%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    &nbsp;N° &nbsp; <span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_numero_trabajo']!='' ? $arrRespuesta[0]['sol_numero_trabajo'] : '__________________'); ?></span>
                                </td>

                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 70%; padding-right: 5px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    &nbsp;Zona: &nbsp; <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_cod_barrio_codigo']>0 ? $arrRespuesta[0]['sol_cod_barrio'] : '__________________________________________________'); ?></span>
                                </td>

                            </tr>

                        </table>
                        
                    </td>
                    
                </tr>
                
                <tr>
                    
                    <td valign="top" style='border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        
                        <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">

                            <tr>

                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 70%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    &nbsp;El lugar es:

                                    &nbsp;

                                    Propio <span class="square"><span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_trabajo_lugar']==1 && $arrRespuesta[0]['sol_ci']!='' ? '&#9745;' : '&#9744;'); ?></span></span>

                                    &nbsp;&nbsp;

                                    Alquilado <span class="square"><span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_trabajo_lugar']==2 && $arrRespuesta[0]['sol_ci']!='' ? '&#9745;' : '&#9744;'); ?></span></span>

                                    &nbsp;&nbsp;

                                    Anticrético <span class="square"><span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_trabajo_lugar']==3 && $arrRespuesta[0]['sol_ci']!='' ? '&#9745;' : '&#9744;'); ?></span></span>

                                </td>
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 10%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: right;'>
                                    &nbsp;Otro:&nbsp;
                                </td>
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 20%; padding-right: 5px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_trabajo_lugar']==99 && $arrRespuesta[0]['sol_ci']!='' ? $arrRespuesta[0]['sol_trabajo_lugar_otro'] : '_______________'); ?></span>
                                </td>

                            </tr>

                        </table>
                        
                    </td>
                    
                </tr>
                
                <tr>
                    
                    <td valign="top" style='border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        
                        <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">

                            <tr>

                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 73%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    &nbsp;Lo realiza en:

                                    <?php $aux_subrayado = 'style="text-decoration: underline;"'; ?>
                                    
                                    <span <?php echo ((int)$arrRespuesta[0]['sol_trabajo_realiza']==1 && $arrRespuesta[0]['sol_ci']!='' ? $aux_subrayado : ''); ?>>Calle&nbsp;</span>/
                                    <span <?php echo ((int)$arrRespuesta[0]['sol_trabajo_realiza']==2 && $arrRespuesta[0]['sol_ci']!='' ? $aux_subrayado : ''); ?>>Tienda&nbsp;</span>/
                                    <span <?php echo ((int)$arrRespuesta[0]['sol_trabajo_realiza']==3 && $arrRespuesta[0]['sol_ci']!='' ? $aux_subrayado : ''); ?>>Puesto de Mercado&nbsp;</span>/
                                    <span <?php echo ((int)$arrRespuesta[0]['sol_trabajo_realiza']==4 && $arrRespuesta[0]['sol_ci']!='' ? $aux_subrayado : ''); ?>>Sindicato&nbsp;</span>/

                                </td>
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 27%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    &nbsp;Otro:
                                    <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_trabajo_realiza']==99 && $arrRespuesta[0]['sol_ci']!='' ? $arrRespuesta[0]['sol_trabajo_realiza_otro'] : '_______________'); ?></span>
                                </td>

                            </tr>

                        </table>
                        
                    </td>
                    
                </tr>
                
                <tr>
                    
                    <td valign="top" style='<?php echo $prop_celda; ?> border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        
                        &nbsp;Actividad del:

                        &nbsp;&nbsp;&nbsp;&nbsp;

                        Deudor <span class="square"><span class="aux_txt"><?php echo '&#9745;'; ?></span></span>

                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        
                        Codeudor <span class="square"><span class="aux_txt"><?php echo '&#9744;'; ?></span></span> 

                        
                    </td>
                    
                </tr>
                
                <tr>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> height: 200px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: center;'>
                        
                        <?php
                        
                            if($arrRespuesta[0]['sol_ci'] != '')
                            {
                                switch ((int)$arrRespuesta[0]['sol_dir_referencia']) {
                                    case 1:
                                        // GEO
                                        $img_aux = $arrRespuesta[0]['sol_geolocalizacion_img'];
                                        break;

                                    case 2:
                                        // Croquis
                                        $img_aux = $this->mfunciones_microcreditos->ConvertToJPEG($arrRespuesta[0]['sol_croquis']);
                                        break;
                                    
                                    default:
                                        $img_aux = '';
                                        break;
                                }
                                
                                if($this->mfunciones_microcreditos->validateIMG_simple($img_aux))
                                {
                                    echo '<img src="' . $img_aux . '" style="height: 200px; max-width: 49%;" />';
                                }
                            }
                        
                        ?>
                        
                    </td>
                    
                </tr>
            
            </table>
            
        </td>
        
        <td valign="top" style='<?php echo $prop_celda; ?> width: 50%; border-right:none;border-bottom:none;border-left:1px solid #000000;border-top:none;text-align: left;'>

            <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">

                <tr>
                    
                    <td valign="top" style='border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        
                        <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">

                            <tr>

                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 54%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    &nbsp;Dirección de la actividad:&nbsp;&nbsp;Calle/Avenida:
                                </td>

                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 46%; padding-right: 5px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    <span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_con_direccion_trabajo']!='' ? implode(' ', array($arrRespuesta[0]['sol_con_direccion_trabajo'], $arrRespuesta[0]['sol_con_edificio_trabajo'])) : '______________________________________'); ?></span>
                                </td>

                            </tr>

                        </table>
                        
                    </td>
                    
                </tr>
                
                <tr>
                    
                    <td valign="top" style='border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        
                        <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">

                            <tr>

                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 30%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    &nbsp;N° &nbsp; <span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_con_numero_trabajo']!='' ? $arrRespuesta[0]['sol_con_numero_trabajo'] : '__________________'); ?></span>
                                </td>

                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 70%; padding-right: 5px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    &nbsp;Zona: &nbsp; <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_cod_barrio_codigo']>0 ? $arrRespuesta[0]['sol_con_cod_barrio'] : '__________________________________________________'); ?></span>
                                </td>

                            </tr>

                        </table>
                        
                    </td>
                    
                </tr>
                
                <tr>
                    
                    <td valign="top" style='border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        
                        <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">

                            <tr>

                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 70%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    &nbsp;El lugar es:

                                    &nbsp;

                                    Propio <span class="square"><span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_trabajo_lugar']==1 && $arrRespuesta[0]['sol_con_ci']!='' ? '&#9745;' : '&#9744;'); ?></span></span>

                                    &nbsp;&nbsp;

                                    Alquilado <span class="square"><span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_trabajo_lugar']==2 && $arrRespuesta[0]['sol_con_ci']!='' ? '&#9745;' : '&#9744;'); ?></span></span>

                                    &nbsp;&nbsp;

                                    Anticrético <span class="square"><span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_trabajo_lugar']==3 && $arrRespuesta[0]['sol_con_ci']!='' ? '&#9745;' : '&#9744;'); ?></span></span>

                                </td>
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 10%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: right;'>
                                    &nbsp;Otro:&nbsp;
                                </td>
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 20%; padding-right: 5px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_trabajo_lugar']==99 && $arrRespuesta[0]['sol_con_ci']!='' ? $arrRespuesta[0]['sol_con_trabajo_lugar_otro'] : '_______________'); ?></span>
                                </td>

                            </tr>

                        </table>
                        
                    </td>
                    
                </tr>
                
                <tr>
                    
                    <td valign="top" style='border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        
                        <table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">

                            <tr>

                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 73%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    &nbsp;Lo realiza en:

                                    <?php $aux_subrayado = 'style="text-decoration: underline;"'; ?>
                                    
                                    <span <?php echo ((int)$arrRespuesta[0]['sol_con_trabajo_realiza']==1 && $arrRespuesta[0]['sol_con_ci']!='' ? $aux_subrayado : ''); ?>>Calle&nbsp;</span>/
                                    <span <?php echo ((int)$arrRespuesta[0]['sol_con_trabajo_realiza']==2 && $arrRespuesta[0]['sol_con_ci']!='' ? $aux_subrayado : ''); ?>>Tienda&nbsp;</span>/
                                    <span <?php echo ((int)$arrRespuesta[0]['sol_con_trabajo_realiza']==3 && $arrRespuesta[0]['sol_con_ci']!='' ? $aux_subrayado : ''); ?>>Puesto de Mercado&nbsp;</span>/
                                    <span <?php echo ((int)$arrRespuesta[0]['sol_con_trabajo_realiza']==4 && $arrRespuesta[0]['sol_con_ci']!='' ? $aux_subrayado : ''); ?>>Sindicato&nbsp;</span>/

                                </td>
                                <td valign="middle" style='<?php echo $prop_celda; ?> width: 27%; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                                    &nbsp;Otro:
                                    <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_con_trabajo_realiza']==99 && $arrRespuesta[0]['sol_con_ci']!='' ? $arrRespuesta[0]['sol_con_trabajo_realiza_otro'] : '_______________'); ?></span>
                                </td>

                            </tr>

                        </table>
                        
                    </td>
                    
                </tr>
                
                <tr>
                    
                    <td valign="top" style='<?php echo $prop_celda; ?> border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: left;'>
                        
                        &nbsp;Actividad del:

                        &nbsp;&nbsp;&nbsp;&nbsp;

                        Deudor <span class="square"><span class="aux_txt"><?php echo '&#9744;'; ?></span></span>

                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        
                        Codeudor <span class="square"><span class="aux_txt"><?php echo ($arrRespuesta[0]['sol_con_ci']!='' && $arrRespuesta[0]['sol_con_ci']!='' ? '&#9745;' : '&#9744;'); ?></span></span>

                        
                    </td>
                    
                </tr>
                
                <tr>
                    
                    <td valign="middle" style='<?php echo $prop_celda; ?> height: 200px; border-right:none;border-bottom:none;border-left:none;border-top:none;text-align: center;'>
                        
                        <?php
                        
                            if($arrRespuesta[0]['sol_con_ci'] != '')
                            {
                                switch ((int)$arrRespuesta[0]['sol_con_dir_referencia']) {
                                    case 1:
                                        // GEO
                                        $img_aux = $arrRespuesta[0]['sol_con_geolocalizacion_img'];
                                        break;

                                    case 2:
                                        // Croquis
                                        $img_aux = $this->mfunciones_microcreditos->ConvertToJPEG($arrRespuesta[0]['sol_con_croquis']);
                                        break;
                                    
                                    default:
                                        $img_aux = '';
                                        break;
                                }
                                
                                if($this->mfunciones_microcreditos->validateIMG_simple($img_aux))
                                {
                                    echo '<img src="' . $img_aux . '" style="height: 200px; max-width: 49%;" />';
                                }
                            }
                        
                        ?>
                        
                    </td>
                    
                </tr>
            
            </table>
            
        </td>
            
    </tr>
    
</table>

<table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td style='border: 0px; font-size:6px; padding: 1px 2px; text-align: justify;' colspan="2">
    Reconozco(cemos) y faculto(amos) a Banco FIE S.A. y a sus funcionarios trabajadores/responsables y/o delegados, debidamente acreditados, a inspeccionar, a obtener información y documentación, cuando así lo estimen necesario y mientras dure la presente relación contractual, respecto a mi (nuestra) situación financiera, así como verificar el destino de la presente solicitud y la(s) garantía(s), quedando obligado el Banco a utilizar la información y documentación únicamente para fines relacionados a la presente solicitud.
    <br />Asímismo, autorizo(amos) también expresamente a BANCO FIE S.A., a consultar durante la vigencia del contrato, mis(nuestros) antecedentes crediticios, tanto en el Buró de Información (BI) como en la Central de Información Crediticia (CIC) de la Autoridad de Supervisión del Sistema Financiero (ASFI) y otras fuentes, así como efectuar reportes a la CIC y verificar mis(nuestros) datos en el Registro Único de Identificación (RUI), administrado por el Servicio General de Identificación Personal (SEGIP); de igual manera y durante la vigencia del contrato, debiendo Banco FIE contar con constancia documentada de la verificación del RUI,  autorizando a compartir esta información  de acuerdo a lo establecido en la Ley de Servicios Financieros y las disposiciones legales de la Unidad de Investigaciones Financieras.  Asimismo, autorizo(amos) a que los funcionarios trabajadores/responsables y/o delegados de BANCO FIE S.A. realicen la verificación de la información domiciliaria y laboral declarada en  la presente solicitud de crédito y sus anexos.
    <br />Autorizo(amos) a Banco FIE a reportar datos del préstamo a la(s) entidad(es) asegurador(as), con el propósito de que ésta(s) cuente(n) con toda la información necesario para la emisión del (de los) Certificado(s) de Cobertura Individual.
    <br />Finalmente,  ratificamos que toda la información declarada en el presente documento es fidedigna  y obedece a la realidad.
    </td>
  </tr>
  <tr>
    <td style='height: 70px; border-right:none;border-top:none;border-bottom:none;border-left:none;text-align: center;'colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td style='border-right:none;border-top:none;border-bottom:none;border-left:none;text-align: center;'>_____________________________</td>
    <td style='border-right:none;border-top:none;border-bottom:none;border-left:none;text-align: center;'>_____________________________</td>
  </tr>
  <tr>
    <td style='border-right:none;border-top:none;border-bottom:none;border-left:none;text-align: center;'>FIRMA DEL SOLICITANTE</td>
    <td style='border-right:none;border-top:none;border-bottom:none;border-left:none;text-align: center;'>FIRMA DEL SOLICITANTE</td>
  </tr>
  <tr>
    <td style='border-right:none;border-top:none;border-bottom:none;border-left:none;text-align: center; height: 5px;'colspan="5"> </td>
  </tr>
</table>
<table style="font-family: Tahoma,Verdana,Segoe,sans-serif;" rules=none width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <th colspan="5" style="<?php echo $prop_celda; ?> text-align:left;background-color:#d6dce4; font-size: 10px;">&nbsp;<u>IX. VERIFICACIÓN DE DOMICILIO Y/O ACTIVIDAD (USO BANCO FIE S.A.)</u></th>
  </tr>
  <tr>
    <td valign="bottom" style='border-top:none;border-bottom:none;border-left:none;text-align: center; height: 45px;'>...................................</td>
    <td valign="bottom" style='border-top:none;border-bottom:none;border-left:none;text-align: center; height: 45px;'>...................................</td>
    <td valign="bottom" style='border-top:none;border-bottom:none;border-left:none;text-align: center; height: 45px;'>...................................</td>
    <td valign="bottom" style='border-top:none;border-bottom:none;border-left:none;text-align: center; height: 45px;'>...................................</td>
    <td valign="bottom" style='border-top:none;border-bottom:none;border-left:none;text-align: center; height: 45px;'>...................................</td>
  </tr>
  <tr>
    <td  style='border-top:none;border-bottom:none;border-left:none;text-align: center;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td  style='border-top:none;border-bottom:none;border-left:none;text-align: center;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td  style='border-top:none;border-bottom:none;border-left:none;text-align: center;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td  style='border-top:none;border-bottom:none;border-left:none;text-align: center;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td  style='border-top:none;border-bottom:none;border-left:none;text-align: center;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tfoot>
    <tr>
      <td style="<?php echo $prop_celda; ?> text-align: left;" colspan="5">
          &nbsp;<b>OFICIAL/EJECUTIVO DE NEGOCIOS</b>
          <span class="aux_txt"><?php echo ((int)$arrRespuesta[0]['sol_asignado']==1 && $arrRespuesta[0]['sol_ci']!='' ? '&nbsp;&nbsp;&nbsp;' . mb_strtoupper($arrRespuesta[0]['agente_nombre']) : ''); ?></span>
      </td>
    </tr>
  </tfoot>
</table>
