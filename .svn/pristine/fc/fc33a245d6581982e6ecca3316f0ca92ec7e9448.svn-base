<?php
$zonahoraria = date_default_timezone_get();
ini_set('date.timezone','America/Lima');
$hoy = date("Y-m-d H:i:s");

for ($i = 0; $i < $countImages; $i++) {
    $image = '<div style="text-align:center"><img style="width:100%;margin:auto" src="'.RUTA_IMAGENES_ADMISION.${"img$i"}.'"></div>';
    $pdfObj->writeHTML($image);
    if($i+1 < $countImages){
        $pdfObj->AddPage();
    }
}

$pdfObj->Output("./uploads/modulos/admision/documentos/".$nombreArchivo, 'F');
