<?php echo "<div class='mensaje_advertencia'> " . $this->lang->line('reportes_ayuda') . " </div> <br />"; ?>

<?php $backgroundColorsList = array("8D8D8D","5DA5DA" ,"FAA43A" ,"60BD68" ,"F17CB0" ,"B2912F" ,"B276B2" ,"DECF3F","58F154","F15854"
    ,"91B22F" ,"76B2B2" ,"CF3FDE","DA8A5D"
);
      $selectedColors = array_values(array_map(function ($i) use ($backgroundColorsList) {return "#".$backgroundColorsList[$i % count($backgroundColorsList)];}, range(0,count($resultado->columnas)-1)));
?>
<div class="ControlColumnasReporte">Contenido Reducido</div>
<div style="width:100%; overflow: hidden" id="divContenedorReporte">
    <canvas id="canvasGrafico" class="chart" style="width:95%" ondblclick="fullScreenChart();"></canvas>
</div>
<?php $funcion_mostrar = $resultado->funcion_mostrar;?>
<script type="text/javascript">
    var backgroundColors = <?php echo json_encode($selectedColors)?>;

    var barChartData2 = {"labels":
        <?php echo json_encode(array_values(array_map(function ($col) {return $col->titulo;},$resultado->columnas)))?>,
        "datasets":[
            <?php foreach ($resultado->columnas as $key=>$columna):?>
            {"label":"ds_<?php echo $key?>",
                "data":
                <?php echo json_encode(array_values(array_map(function ($fila) use ($funcion_mostrar, $key) {$valor = $funcion_mostrar($fila->etapas, $key); return is_numeric($valor)?$valor:0;},$resultado->filas)))?>
            },
            <?php endforeach;?>
        ]};
    var colorId=0;
    var barChartData = {"labels":
        <?php echo json_encode(array_values(array_map(function ($fila) use ($resultado) {return implode(" - ",array_values(array_map(function ($col) use ($fila, $resultado) {return $fila->{"campo_grupo_".$col->alias_sql};},$resultado->columnas_grupo))); },$resultado->filas)))?>,
        "datasets":[
            <?php foreach ($resultado->columnas as $key=>$columna):?>
            {"label":"<?php echo $columna->titulo?>",
                backgroundColor: backgroundColors[(colorId++) % backgroundColors.length],
                "data":
                <?php echo json_encode(array_values(array_map(function ($fila) use ($funcion_mostrar, $key) {$valor = $funcion_mostrar($fila->etapas, $key); return is_numeric($valor)?$valor:0;},$resultado->filas)))?>
            },
            <?php endforeach;?>
        ]};

//        [{"label":"test","data":[10,20,30,40,50]}];
  //  var barChartData = [{"label":"test","data":[10,20,30,40,50]}];
    $(document).ready(function(){
       var ctx = $("#canvasGrafico")[0].getContext("2d");
       Chart.defaults.global.tooltips.callbacks.label = function (tooltipItem, data) {
           var dataset = data.datasets[tooltipItem.datasetIndex];
           var datasetLabel = dataset.label || '';
           return datasetLabel + ": " + dataset.data[tooltipItem.index].toLocaleString();
       };
        Chart.scaleService.updateScaleDefaults('linear', {
            ticks: {
                callback: function(tick) {
                    return tick.toLocaleString();
                }
            }
        });
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) {if (value % 1 === 0) {return value;}}
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            autoSkip: false
                        },
                        scaleLabel: {
                            display:true,
                            labelString: "<?php echo implode(" - ",array_values(array_map(function ($col) {return $col->titulo;},$resultado->columnas_grupo)));?>",
                        }
                    }]
                },
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: ''
                }
            }
        });
        $(window).resize(verificaAnchoPantalla);
        verificaAnchoPantalla();
    });

    function verificaAnchoPantalla() {
        var colapsarDatos = ($(".ControlColumnasReporte").css("display")!="none");
        $("#divContenedorReporte").css("zoom",colapsarDatos?"50%":"100%");
    }

    function fullScreenChart(){
        var elem = $("#divContenedorReporte")[0];
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen();
        }
    }

</script>
