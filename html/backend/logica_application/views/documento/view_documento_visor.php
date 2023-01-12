
<?php

$decoded = base64_decode($documento_base64);
header('Content-Type: application/pdf');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: inline; filename=" . $nombre_download);
echo $decoded;
?>
