    
<script type="text/javascript">

    function VerTablaResumenExc(id) {

        $("#"+id).slideToggle();
    }

</script>

    <div style="overflow-y: auto; height: 400px;">
        
        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('DetalleHistorialSeguimiento') . ' ' . PREFIJO_PROSPECTO . $codigo_prospecto ; ?></div>

        <div style="clear: both"></div>

        <br />

        <form id="FormularioRegistroLista" method="post">

            <div id="2">

                <?php

                if(count($arrObsExcepcion[0]) > 0)
                {

                ?>

                    <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 100%;">
                        <thead>

                            <tr class="FilaCabecera">

                                <th style="width:5%;">
                                    N°
                                 </th>
                                
                                <!-- Similar al EXCEL -->

                                <th style="width:15%;">
                                   <strong> <?php echo $this->lang->line('observacion_fecha'); ?> </strong> </th>
                                <th style="width:15%;">
                                   <strong> <?php echo $this->lang->line('observacion_usuario_realizado'); ?> </strong> </th>                                
                                <th style="width:15%;">
                                   <strong> <?php echo $this->lang->line('observacion_usuario_accion'); ?> </strong> </th>
                                <th style="width:20%;">
                                   <strong> <?php echo $this->lang->line('observacion_derivado_etapa'); ?> </strong> </th>
                                <th style="width:30%;">
                                   <strong> <?php echo $this->lang->line('observacion_excepcion_detalle'); ?> </strong> </th>

                                <!-- Similar al EXCEL -->

                            </tr>
                        </thead>
                        <tbody>
                        <?php

                        $mostrar = true;
                        if (count($arrObsExcepcion[0]) == 0) 
                        {
                            $mostrar = false;
                        }

                        if ($mostrar) 
                        {
                            $i=0;
                            $strClase = "FilaBlanca";
                            foreach ($arrObsExcepcion as $key => $value)
                            {                    
                                $i++;

                                ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="text-align: center;">
                                        <?php echo $i; ?>
                                    </td>
                                    
                                    <!-- Similar al EXCEL -->

                                    <td style="text-align: center;">
                                        <?php echo $value["accion_fecha"]; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php echo $value["usuario_nombre"]; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php echo $value["seguimiento_accion"]; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php echo $value["etapa_nombre"]; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php echo $value["seguimiento_detalle"]; ?>
                                    </td>                               

                                    <!-- Similar al EXCEL -->

                                </tr>
                            <?php
                            }
                            ?>
                </tbody>
                            <?php
                            $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca";
                            //endfor;
                        }?>
            </table>

                <?php

                }

                else
                {            
                    echo $this->lang->line('TablaNoRegistros');
                }

                ?>

            </div>

        </form>

        <br /><br />
    </div>