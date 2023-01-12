<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<div align="center" style="width: 1000px;">
	
	<table width="900" border="0" style="border-collapse: collapse; font-family: 'Open Sans', Arial, sans-serif; font-size: 18px;">
		<tbody>
			<tr>
                            <td style="padding: 5px;" align="left"> <img style="height: 100px;" src="<?php echo $this->config->base_url(); ?>html_public/imagenes/reportes/logo_atc.jpg" /> </td>
			  <td style="padding: 5px; font-size: 18px; font-weight: bold;" align="center">
                              REGISTRO <br /> EVALUACIÓN LEGAL PARA EMPRESAS ACEPTANTES
                          </td>
			  <td style="padding: 5px;" align="right"> <img style="height: 100px;" src="<?php echo $this->config->base_url(); ?>html_public/imagenes/reportes/logo_red_enlace.jpg" /> </td>
			</tr>
		</tbody>
	</table>
		
	<br />
		
  <table width="900" border="0" style="border-collapse: collapse; font-family: 'Open Sans', Arial, sans-serif; font-size: 14px;">
		<tbody>
			<tr>
				<td style="padding: 5px; border: 2px solid #000000; background-color: #d9d9d9; font-weight: bold;" align="left">
					I. DATOS GENERALES
			  </td>
			</tr>
		</tbody>
	</table>
  
  	<br />
  
  	<table width="900" border="0" style="border-collapse: collapse; font-family: 'Open Sans', Arial, sans-serif; font-size: 14px;">
		<tbody>
			<tr>
			  <td style="padding: 5px; border: 2px solid #000000; width: 197px; font-weight: bold;" align="justify">
					<?php echo $this->lang->line('evaluacion_denominacion_comercial'); ?>
				</td>
			  <td style="padding: 5px; border: 2px solid #000000; width: 663px;" align="justify">
					<?php echo $arrResultado[0]["evaluacion_denominacion_comercial"]; ?>
			  </td>
			</tr>
			<tr>
			  <td style="padding: 5px; border: 2px solid #000000; width: 197px; font-weight: bold;" align="justify">
					<?php echo $this->lang->line('evaluacion_razon_social'); ?>
				</td>
			  <td style="padding: 5px; border: 2px solid #000000; width: 663px;" align="justify">
					<?php echo $arrResultado[0]["evaluacion_razon_social"]; ?>
			  </td>
			</tr>
		</tbody>
	</table>
  
  	<br />
  
  	<table width="900" border="0" style="border-collapse: collapse; font-family: 'Open Sans', Arial, sans-serif; font-size: 14px;">
		<tbody>
			<tr>
				<td style="padding: 5px; border: 2px solid #000000; background-color: #d9d9d9; font-weight: bold;" align="left">
					II. EVALUACIÓN DOCUMENTAL
				</td>
			</tr>
		</tbody>
	</table>
  
  	<br />
	
	<table width="900" border="0" style="border-collapse: collapse; font-family: 'Open Sans', Arial, sans-serif; font-size: 14px;">
		<tbody>
			<tr>
				<td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 30px;"><strong>N°</strong></td>
				<td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong>DOCUMENTO</strong></td>
				<td colspan="4" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong>ANÁLISIS LEGAL</strong></td>
			</tr>
			<tr>
			  <td rowspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 30px;"><strong> 1 </strong></td>
			  <td rowspan="2" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px; background-color: #d9d9d9; font-weight: bold;"><?php echo $this->lang->line('evaluacion_fotocopia_nit'); ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_nit'); ?></strong></td>
			  <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_nit"]; ?></td>
		  </tr>
			<tr>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_representante_legal'); ?></strong></td>
			  <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_representante_legal"]; ?></td>
		  </tr>
			<tr>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><?php echo $arrResultado[0]["evaluacion_doc_nit"]; ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_razon_idem'); ?></strong></td>
			  <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_razon_idem"]; ?></td>
		  </tr>
			<tr>
			  <td rowspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 30px;"><strong>2</strong></td>
			  <td rowspan="2" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px; background-color: #d9d9d9; font-weight: bold;"><?php echo $this->lang->line('evaluacion_fotocopia_certificado'); ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_actividad_principal'); ?></strong></td>
			  <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_actividad_principal"]; ?></td>
		  </tr>
			<tr>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_actividad_secundaria'); ?></strong></td>
			  <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_actividad_secundaria"]; ?></td>
		  </tr>
			<tr>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><?php echo $arrResultado[0]["evaluacion_doc_certificado"]; ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_razon_idem'); ?></strong></td>
			  <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_razon_idem"]; ?></td>
		  </tr>
			<tr>
			  <td rowspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 30px;"><strong>3</strong></td>
			  <td rowspan="2" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px; background-color: #d9d9d9; font-weight: bold;"><?php echo $this->lang->line('evaluacion_fotocopia_ci'); ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_ci_pertenece'); ?></strong></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"> <?php echo $arrResultado[0]["evaluacion_ci_pertenece"]; ?> </td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><strong><?php echo $this->lang->line('evaluacion_ci_vigente'); ?></strong></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_ci_vigente"]; ?></td>
		  </tr>
			<tr>
			  <td height="33" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_ci_fecnac'); ?></strong></td>
			  <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_ci_fecnac"]; ?></td>
		  </tr>
			<tr>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><?php echo $arrResultado[0]["evaluacion_doc_ci"]; ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_ci_titular'); ?></strong></td>
			  <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_ci_titular"]; ?></td>
		  </tr>
			<tr>
			  <td rowspan="4" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 30px;"><strong>4</strong></td>
			  <td rowspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px; background-color: #d9d9d9; font-weight: bold;"><?php echo $this->lang->line('evaluacion_fotocopia_testimonio'); ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_numero_testimonio'); ?></strong></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_numero_testimonio"]; ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><strong><?php echo $this->lang->line('evaluacion_duracion_empresa'); ?></strong></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_duracion_empresa"]; ?></td>
		  </tr>
			<tr>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_fecha_testimonio'); ?></strong></td>
			  <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_fecha_testimonio"]; ?></td>
		  </tr>
			<tr>
			  <td rowspan="2" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_objeto_constitucion'); ?></strong></td>
                          <td colspan="3" rowspan="2" align="justify" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"> <?php echo $arrResultado[0]["evaluacion_objeto_constitucion"]; ?> &nbsp; </td>
		  </tr>
			<tr>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><?php echo $arrResultado[0]["evaluacion_doc_test"]; ?></td>
		  </tr>
			<tr>
			  <td rowspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 30px;"><strong>5</strong></td>
			  <td rowspan="2" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px; background-color: #d9d9d9; font-weight: bold;"><?php echo $this->lang->line('evaluacion_fotocopia_poder'); ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_fecha_testimonio_poder'); ?></strong></td>
                          <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_fecha_testimonio_poder"]; ?></td>
		  </tr>
			<tr>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_numero_testimonio_poder'); ?></strong></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_numero_testimonio_poder"]; ?></td>
			  <td rowspan="2" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><strong><?php echo $this->lang->line('evaluacion_facultad_representacion'); ?></strong></td>
			  <td rowspan="2" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_facultad_representacion"]; ?></td>
		  </tr>
			<tr>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><?php echo $arrResultado[0]["evaluacion_doc_poder"]; ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_firma_conjunta'); ?></strong></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_firma_conjunta"]; ?></td>
		  </tr>
			<tr>
			  <td rowspan="4" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 30px;"><strong>6</strong></td>
			  <td rowspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px; background-color: #d9d9d9; font-weight: bold;"><?php echo $this->lang->line('evaluacion_fotocopia_fundaempresa'); ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_fundaempresa_emision'); ?></strong></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_fundaempresa_emision"]; ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><strong><?php echo $this->lang->line('evaluacion_fundaempresa_vigencia'); ?></strong></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_fundaempresa_vigencia"]; ?></td>
		  </tr>
			<tr>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_idem_escritura'); ?></strong></td>
			  <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_idem_escritura"]; ?></td>
		  </tr>
			<tr>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_idem_poder'); ?></strong></td>
			  <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_idem_poder"]; ?></td>
		  </tr>
			<tr>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><?php echo $arrResultado[0]["evaluacion_doc_funde"]; ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_idem_general'); ?></strong></td>
			  <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_idem_general"]; ?></td>
		  </tr>
			<tr>
			  <td rowspan="4" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 30px;">  </td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_resultado'); ?></strong></td>
			  <td colspan="4" align="justify" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><?php echo $arrResultado[0]["evaluacion_resultado"]; ?></td>
		  </tr>
			<tr>
			  <td rowspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_titulo_opcion'); ?></strong></td>
			  <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><?php echo $this->lang->line('evaluacion_conclusion1'); ?></td>
                          <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px; font-weight: bold;"><?php if($arrResultado[0]['opcion_conclusion'] == 1){ echo "X"; } ?></td>
		  </tr>
			<tr>
			  <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><?php echo $this->lang->line('evaluacion_conclusion2'); ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px; font-weight: bold;"><?php if($arrResultado[0]['opcion_conclusion'] == 2){ echo "X"; } ?></td>
		  </tr>
			<tr>
			  <td colspan="3" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><?php echo $this->lang->line('evaluacion_conclusion3'); ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px; font-weight: bold;"><?php if($arrResultado[0]['opcion_conclusion'] == 3){ echo "X"; } ?></td>
		  </tr>
			<tr>
			  <td rowspan="5" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 30px;">  </td>
			  <td rowspan="2" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"> <img style="height: 100px;" src="<?php echo $this->config->base_url(); ?>html_public/imagenes/reportes/logo_atc.jpg" /> </td>
			  <td rowspan="2" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><?php echo $this->lang->line('evaluacion_pertenece_regional'); ?></strong></td>
			  <td rowspan="2" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><?php echo $arrResultado[0]["evaluacion_pertenece_regional"]; ?></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><strong><?php echo $this->lang->line('evaluacion_fecha_solicitud'); ?></strong></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_fecha_solicitud"]; ?></td>
		  </tr>
			<tr>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><strong><?php echo $this->lang->line('evaluacion_fecha_evaluacion'); ?></strong></td>
			  <td align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 140px;"><?php echo $arrResultado[0]["evaluacion_fecha_evaluacion"]; ?></td>
		  </tr>
			<tr>
			  <td colspan="5" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong><em><u>REVISADO POR:</u></em></strong></td>
		  </tr>
			<tr>
			  <td colspan="5" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px; height: 200px;">  </td>
		  </tr>
			<tr>
			  <td colspan="5" align="center" valign="middle" style="padding: 5px; border: 2px solid #000000; width: 175px;"><strong>ANALISTA LEGAL/SUPERVISOR LEGAL</strong></td>
		  </tr>
		</tbody>
	</table>
	
	<br />
	
</div>