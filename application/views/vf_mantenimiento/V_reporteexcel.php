<?php
$nomArchivo = "Error_".(date('d-m-Y h:i:s A')).".xls";
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment;filename=".$nomArchivo);
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php echo $tabla;?>