<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<div style="font-size: 14px; font-family: 'Open Sans', Arial, sans-serif; text-align: center; font-weight: bold;"><?php echo $this->lang->line('title_result_ad') ?></div>

<br />

    <table align="center" id="divDetalle" class="tblListas Centrado " cellspacing="0" border="0" style="border: 1px solid #000000; width: 100%; font-size: 10px; font-family: 'Open Sans', Arial, sans-serif; text-align: center;">

        <?php $strClase = "FilaGris";?>
        <tr class="FilaCabecera">

            <th style="width: 40%; text-align: left !important;">
                <strong> &nbsp;<?php echo $this->lang->line('subtitle_result_ad') ?>  </strong>
                <?php echo $reporte_ad->total; ?>
            </th>

        </tr>

        <tr  class="<?php echo $strClase; ?>">

            <td colspan="2" style="width: 100%; text-align: center;">

                <table style="width: 100%; border: 0px;">
                    <tr>
                        <td valign="top" style="width: 46%; text-align: left; vertical-align: top !important; padding: 2px 4px;">
                            <?php echo $filtro_texto; ?>
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
                <th style="width: 10%"> <?php echo $this->lang->line('id_table_ad') ?> </th>
                <th style="width: 29%"> <?php echo $this->lang->line('params_table_ad') ?> </th>
                <th style="width: 10%"> <?php echo $this->lang->line('cod_err_table_ad') ?> </th>
                <th style="width: 20%"> <?php echo $this->lang->line('message_table_ad') ?> </th>
                <th style="width: 15%"> <?php echo $this->lang->line('fec_sol_table_ad') ?> </th>
                <th style="width: 15%"> <?php echo $this->lang->line('ip_table_ad') ?> </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reporte_ad->array_listado as $key => $value): ?>
            <tr class="FilaBlanca">
                        <td style="text-align: center;">
                            <?php echo $key+1; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo json_decode($value['auditoria_params'], true); ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['auditoria_cod_error']; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['auditoria_mensaje']; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['auditoria_fecha']; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value['auditoria_ip']; ?>
                        </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
