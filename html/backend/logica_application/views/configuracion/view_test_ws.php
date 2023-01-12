
<div style="overflow-y: auto; height: 400px;">

    <div class="FormularioSubtitulo" style="width: 90% !important;"> <?php echo $this->lang->line('conf_test_ws_titulo') . ' - ' . date('d/m/Y H:i:s'); ?></div>

    <div style="clear: both"></div>

    <br />

    <div style="text-align: left;">
        <pre>
            <?php
                echo "<i>Time: " . number_format($time, 2, '.', ',') . ' seg.</i><br /><br />';
                
                print_r($resultado_soa_fie); 
            ?>
        </pre>
    </div>

    <br /><br />
</div>