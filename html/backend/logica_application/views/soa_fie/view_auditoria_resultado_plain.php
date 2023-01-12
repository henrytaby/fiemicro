<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<div style="font-size: 14px; font-family: 'Open Sans', Arial, sans-serif; text-align: center; font-weight: bold;">REPORTE REGISTROS AUDITORÍA SOA-FIE</div>

<br />

    <table align="center" id="divDetalle" class="tblListas Centrado " cellspacing="0" border="0" style="border: 1px solid #000000; width: 100%; font-size: 10px; font-family: 'Open Sans', Arial, sans-serif; text-align: center;">

        <?php $strClase = "FilaGris"; ?>
        <tr class="FilaCabecera">

            <th style="width: 40%; text-align: left !important;">
                <strong> &nbsp; TOTAL CANTIDAD DE REGISTROS OBTENIDOS: </strong>
                <?php echo $reporte_soa->total; ?>
            </th>

            <th style="width: 60%; text-align: center;">
                <span style="font-weight: bold; font-size: 1.2em;">RESUMEN DEL REPORTE</span>
            </th>

        </tr>

        <tr  class="<?php echo $strClase; ?>">

            <td colspan="2" style="width: 100%; text-align: center;">

                <table style="width: 100%; border: 0px;">
                    <tr>
                        <td valign="top" style="width: 46%; text-align: left; vertical-align: top !important; padding: 2px 4px;">
                            <?php echo $filtro_texto; ?>
                        </td>
                        <td valign="top" style="width: 18%; text-align: left; vertical-align: top !important; padding: 2px 4px;">
                            <strong>Por Servicio</strong>
                            <br /> • Validación COBIS-SEGIP: <?php echo $reporte_soa->servicio_segip; ?>
                            <br /> • Prueba de Vida: <?php echo $reporte_soa->servicio_liveness; ?>
                            <br /> • OCR: <?php echo $reporte_soa->servicio_ocr; ?>
                            <br /> • Otros: <?php echo $reporte_soa->servicio_otro; ?>
                        </td>
                        <td valign="top" style="width: 18%; text-align: left; vertical-align: top !important; padding: 2px 4px;">
                            <strong>Tipo de Respuesta</strong>
                            <br /> • Exitoso: <?php echo $reporte_soa->respuesta_success; ?>
                            <br /> • Error: <?php echo $reporte_soa->respuesta_error; ?>
                        </td>
                        <td valign="top" style="width: 18%; text-align: left; vertical-align: top !important; padding: 2px 4px;">
                            <strong>Tipo de Validación</strong>
                            <br /> • Primer intento: <?php echo $reporte_soa->validacion_primero; ?>
                            <br /> • Reintento: <?php echo $reporte_soa->validacion_reintento; ?>
                        </td>
                    </tr>                            
                </table>

            </td>

        </tr>

    </table>

<br />

    <table align="center" id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 100%; font-size: 10px; font-family: 'Open Sans', Arial, sans-serif; text-align: center;">
        <thead>
        <tr>
            <th style="width:9%;"> Token de registro front-end </th>
            <th style="width:8%;"> Número CI </th>
            <th style="width:9%;"> Nombre del Servicio </th>
            <th style="width:8%;"> Fecha y hora de consumo del servicio </th>
            <th style="width:8%;"> Tipo Validación </th>
            <th style="width:8%;"> Tipo Respuesta </th>
            <th style="width:8%;"> Validación Cliente COBIS </th>
            <th style="width:9%;"> Código respuesta SEGIP </th>
            <th style="width:8%;"> Resultado Prueba de Vida </th>
            <th style="width:8%;"> Resultado comparación Selfie vs. Foto SEGIP </th>
            <th style="width:8%;"> Resultado comparación Selfie vs. Foto CI </th>
            <th style="width:9%;"> Porcentaje de coincidencia OCR vs. Registro Cliente </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reporte_soa->array_listado as $key => $value) :?>
            <tr class="FilaBlanca">
                <td style="text-align: center;">
                    <?php echo $value['token']; ?>
                </td>

                <td style="text-align: center;">
                    <?php echo $value['nro_ci']; ?>
                </td>

                <td style="text-align: center;">
                    <?php echo $value['servicio_nombre']; ?>
                </td>

                <td style="text-align: center;">
                    <?php echo $value['servicio_fecha']; ?>
                </td>

                <td style="text-align: center;">
                    <?php echo $value['tipo_validacion']; ?>
                </td>

                <td style="text-align: center;">
                    <?php echo $value['tipo_respuesta']; ?>
                </td>

                <td style="text-align: center;">
                    <?php echo $value['cliente_cobis']; ?>
                </td>

                <td style="text-align: center;">
                    <?php echo $value['respuesta_segip']; ?>
                </td>

                <td style="text-align: center;">
                    <?php echo $value['respuesta_prueba_vida']; ?>
                </td>

                <td style="text-align: center;">
                    <?php echo $value['comparacion_selfie_segip']; ?>
                </td>

                <td style="text-align: center;">
                    <?php echo $value['comparacion_selfie_ci']; ?>
                </td>

                <td style="text-align: center;">
                    <?php echo $value['porcentaje_similaridad']; ?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
