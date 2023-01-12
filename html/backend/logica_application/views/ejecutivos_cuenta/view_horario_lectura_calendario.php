<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link rel='stylesheet' href='../../../html_public/js/lib/fullcalendar/lib/cupertino/jquery-ui.min.css' />
<link href='../../../html_public/js/lib/fullcalendar/fullcalendar.min.css' rel='stylesheet' />
<link href='../../../html_public/js/lib/fullcalendar/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<script src='../../../html_public/js/lib/fullcalendar/lib/moment.min.js'></script>
<script src='../../../html_public/js/lib/fullcalendar/lib/jquery.min.js'></script>
<script src='../../../html_public/js/lib/fullcalendar/fullcalendar.min.js'></script>
<script src='../../../html_public/js/lib/fullcalendar/locale/es.js'></script>
<script src='../../../html_public/js/lib/fullcalendar/gcal.min.js'></script>
<script>

    $(document).ready(function() {

        $('#calendar').fullCalendar({
            theme: true,
            header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listMonth'
            },
            //defaultDate: '2017-05-12',
            navLinks: true, // can click day/week names to navigate views
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            
            <?php
            
            if($conf_calendario == 1)
            {
            
            ?>
                // -- FERIADOS NACIONALES INICIO --
                googleCalendarApiKey: '<?php echo $conf_key_google; ?>',

                eventClick: function(event) {
                        // opens events in a popup window
                        //window.open(event.url, 'gcalevent', 'width=700,height=600');
                        return false;
                },
                        
                eventColor: '#a50909',
            
                // -- FERIADOS NACIONALES FIN --
                        
            <?php
            
            }
            
            if($conf_horario_laboral == 1)
            {
            
            ?>
                businessHours:
                {
                    start: '<?php echo $conf_atencion_desde; ?>',
                    end: '<?php echo $conf_atencion_hasta; ?>',
                    dow: [<?php echo $conf_atencion_dias; ?>]
                },
            
            <?php
            
            }
            else
            {
            
            ?>
                        
                businessHours:
                {
                    start: '00:01', 
                    end: '23:59',
                    dow: [ 0, 1, 2, 3, 4, 5, 6]
                },
                        
            <?php
            
            }
            
            ?>
            
            events: [
                
                <?php
                    
                    if(count($arrRespuesta[0]) > 0)
                    {
                        foreach ($arrRespuesta as $key => $value) 
                        {
                            echo "
                                    {
                                        id: '" . $value["cal_id"] . "',
                                        title: '" . str_replace("'", "\'", $value["empresa_nombre"]) . "',
                                        start: '" . $value["cal_visita_ini"] . "',
                                        end: '" . $value["cal_visita_fin"] . "',
                                        constraint: 'businessHours',
                                        color: '" . $value["evento_color"] . "'
                                    },
                                ";
                        }
                    }
                
                ?>
            ],
            
            eventDrop: function(event, delta, revertFunc) {
                
                var id = event.id;
                var start = event.start.format();
                var end = (event.end == null) ? start : event.end.format();
                
                $.post(
                        "Guardar", 
                        { 'id': id, 'start': start, 'end': end }
                )
                
                .done(function(data) {
                    alert(" :: Horario Guardado Correctamente :: ");
                });
            },
            
            eventResize: function(event, delta) {
                
                var id = event.id;
                var start = event.start.format();
                var end = (event.end == null) ? start : event.end.format();
                
                $.post(
                        "Guardar", 
                        { 'id': id, 'start': start, 'end': end }
                )
                
                .done(function(data) {
                    alert(" :: Horario Guardado Correctamente :: ");
                });
            },
            
            loading: function(bool) {
                if (bool) $('#loading').show();
                else $('#loading').hide();
            }
        });
        
        <?php
            
        if($conf_calendario == 1)
        {
            echo "$('#calendar').fullCalendar('addEventSource', 'es.bo#holiday@group.v.calendar.google.com');";
        }
        ?>
        
            
            

    });

</script>
<style>

	body {
            margin: 40px 10px;
            padding: 0;
            font-family: 'Open Sans', Arial, sans-serif;
            font-size: 11px;
	}

	#calendar {
            max-width: 100%;
            margin: 0 auto;
            height: 350px;
            overflow-y: auto;
	}
        
        #loading {
            position: absolute;
            top: 5px;
            right: 5px;
            color: #ffffff;
            font-size: 10px;
            border: 3px solid #ffffff;
            background-color: #006699;
            border-radius: 20px;
            font-weight: bold;
            width: 150px;
            text-align: center;
            padding-top: 2px;
            padding-right: 2px;
            padding-bottom: 2px;
            padding-left: 2px;
        }

</style>
</head>
<body>

	<div id='calendar'></div>
        <div id='loading' style='display:none'>Cargando Calendario...</div>
</body>
</html>
