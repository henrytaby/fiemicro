
<style>

    body {
    font-family: \'Roboto\', sans-serif;
    background-color: #fafafa;
    }
    table 
    {
            width: 90%;
            margin-left: 5%;
            margin-right: 5%;
            margin-bottom: 10px;
            border-collapse: collapse;
            padding: 0px;
            box-sizing: border-box;
            box-shadow: 1px 2px 5px 0px #ccc;
            background-color: #ffffff !important;
    }
    /* top-left border-radius */
    table tr:first-child th:first-child,
    table.Info tr:first-child td:first-child {
            border-top-left-radius: 10px;
    }
    /* top-right border-radius */
    table tr:first-child th:last-child,
    table.Info tr:first-child td:last-child {
            border-top-right-radius: 10px;
    }
    /* bottom-left border-radius */
    table tr:last-child td:first-child {
            border-bottom-left-radius: 10px;
    }
    /* bottom-right border-radius */
    table tr:last-child td:last-child {
            border-bottom-right-radius: 10px;
    }
    th {
            line-height: 1.3em;
            vertical-align: middle;
            background-color: #006699;
            color: #ffffff !important;
            font-size: 11px !important;
            letter-spacing: 0px !important;
            padding: 8px 5px !important;
    }
    td {
            border: 1px solid #CCC;
            border-top: 1px solid #f8f8f8;
            line-height: 1.3em;
            padding: 8px 12px;
            vertical-align: middle;
            text-align: center;
            font-size: 11px !important;
    }
    h1 {
            font-size: 11px;
            color: #006699;
            letter-spacing: 0.5px;
            font-weight: bold;
            text-align: left;
            text-shadow: #004162 0px 1px 1px;
    }
    h2 {
            font-size: 11px;
            color: #006699;
            letter-spacing: 0.5px;
            font-weight: bold;
            text-align: left;
            text-shadow: #004162 0px 1px 1px;
    }
    h4 {
            font-size: 11px;
            color: #666666;
            font-weight: normal;
            text-align: left;
            text-shadow: #222222 0px 1px 1px;
            margin: 0px;
            text-align: center;
    }
    h5 {
            font-size: 11px;
            color: #006699;
            letter-spacing: 0.5px;
            font-weight: bold;
            text-align: center;
            text-shadow: #004162 0px 1px 1px;
            margin: 0px;
    }
    h6 {
            font-size: 11px;
            color: #006699;
            letter-spacing: 0.5px;
            font-weight: bold;
            text-align: center;
            text-shadow: #004162 0px 1px 1px;
            margin: 0px;
    }
    p {
            font-weight: 500;
            line-height: 20px;
            margin: 0 0 10px;
    }
    fieldset {
            border: 2px solid #006699;
            border-radius: 10px;
            padding: 15px 15px 5px 15px;
            width: 90%;
            background-color: #f5f5f5 !important;
    }
    fieldset legend {
            background: #006699;
            color: #fff;
            padding: 8px 10px;
            border-radius: 5px;
            border: 2px solid #ffffff;
            text-shadow: #004162 0px 1px 1px;
            font-weight: bold;
            font-size: 11px;
            width: 90%;
            margin-left: 0px;
            margin-bottom: 0px !important;
    }
    img {
            width: 100%;
            height: auto;
    }
    .img_derecha {
            width: 50%;
            float: right;
    }

    .barra_contenedor
    {
            width: 100%;
            margin: 0px 0px 15px 0px;
            border-left: 1px dashed #ccc;
    }

    .barra_color
    {
            height: 30px;
            border-radius: 20px 5px 20px 5px;
            box-shadow: 0px 3px 3px 0 rgba(0, 0, 0, 0.2), -1px 3px 3px 0 rgba(0, 0, 0, 0.19);
    }

    .barra_texto
    {
            font-size: 11px;
            text-align: left;
            margin-top: -23px;
            padding-left: 15px;
            color: #eeeeee;
            font-weight: bold;
            text-shadow: #333333 0px 1px 1px;
    }

    .barra_titulo
    {
            font-size: 11px;
            padding-left: 15px;
            font-weight: normal;
            color: #aaaaaa;
            text-shadow: #222222 0px 1px 1px;
            margin: 0px;
            padding: 0px 0px 0px 15px;
    }

    .texto_contenido
    {
            width: 30px;
    }

    .texto_bloque
    {
            width: 50%;
            float: left;
            margin-bottom: 10px;
    }

    .texto_bloque3
    {
            width: 33%;
            float: left;
            margin-bottom: 10px;
    }
    
</style>

<div style="overflow-y: auto; height: 400px;">

    <div class="FormularioSubtitulo" style="width: 100%;"> <i class="fa fa-inbox" aria-hidden="true"></i> <?php echo $NombreEjecutivo; ?></div>
    
    <div style="clear: both"></div>
    
    <br />
    
    <div style="text-align: center;"><h5 style="font-size: 18px;">ESTADO DE EVOLUCIÓN</h5></div>
    
    <br />
    
    <?php echo str_replace('<br />', '', $html_body); ?>

</div>