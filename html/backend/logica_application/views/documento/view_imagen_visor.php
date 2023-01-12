<?php

$imagen_tipo = substr($documento_base64, 5, strpos($documento_base64, ';')-5);

$documento_base64 = str_replace(" ", "", $documento_base64);

$imagen = explode(",", $documento_base64);

header("Content-type: " . $imagen_tipo);
$data = $imagen[1];
echo base64_decode($data);
?>
