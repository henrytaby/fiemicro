
<?php

if(isset($_SESSION["arrListaRol"]) && count($_SESSION["arrListaRol"]) > 0)
{
?>
    <br /><br />
    <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

        <?php $strClase = "FilaGris"; ?>
        
        <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
        <tr class="FilaGris">
            
            <td colspan="3" style="font-weight: bold; text-align: center;">
                Listado Roles Adicionados
            </td>
            
        </tr>
        
        <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
        <tr class="FilaGris">
            
                <td style="width: 10%; font-weight: bold; text-align: center;">
                    N°
                </td>

                <td style="width: 80%; font-weight: bold; text-align: center;">
                    Nombre Rol
                </td>

                <td style="width: 10%; font-weight: bold; text-align: center;">
                    <?php echo $this->lang->line('TablaOpciones'); ?>
                </td>
        </tr>

        <?php
        
        $i = 1;
        
        foreach ($_SESSION["arrListaRol"] as $key => $value) {
        ?>
        
            <?php $strClase = "FilaBlanca"; ?>
        
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                    <td style="text-align: center;">
                        <?php echo $i++; ?>
                    </td>

                    <td style="text-align: center;">
                        <?php echo $value["nombre"]; ?>
                    </td>

                    <td style="text-align: center;">
                        <span class="EnlaceSimple" onclick="QuitarRegistroListaRol(<?php echo $key; ?>);">
                            <i class="fa fa-times" aria-hidden="true"></i> Quitar
                        </span>
                    </td>
            </tr>

        <?php
        }
        ?>
            
    </table>

<?php
}
else
{
    echo '<br />'. $this->lang->line('TablaNoRegistros');
}
?>