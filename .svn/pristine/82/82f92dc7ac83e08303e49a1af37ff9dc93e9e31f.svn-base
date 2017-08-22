<?php
$zonahoraria = date_default_timezone_get();
ini_set('date.timezone','America/Lima');
$hoy = date("d/m/Y h:i:s A");

$fondo = 
'  <!DOCTYPE html>
   <html lang="en"> 
       <head>
           <meta charset="utf-8">
           <meta http-equiv="X-UA-Compatible" content="IE=edge">
           <meta name="viewport" content="width=device-width, initial-scale=1">

           <link type="text/css" rel="stylesheet" href="'.base_url().'public/css/fonts/roboto.css"/>
           <link type="text/css" rel="stylesheet" href="'.base_url().'public/plugins/mdl/css/material.min.css">
           <style>
               table {
                   margin-bottom:15px;
                   border-collapse: collapse;
                   width: 100%;
               }
               td,th{
                   border:1px solid #000;
                   text-align: left;
                   padding: 8px;
               }
               
           </style>          
       </head>             
       <body style="width:100%;height:225px;filter:alpha(opacity=25);-moz-opacity:.25;opacity:.25;">
       <a href="http://smiledu.pe/" target="_blank" style="position: absolute; top: 10px; left: 10px;"><img src="'.base_url().'public/img/menu/logo_smiledu.png" title="logo" style="height: 45px;"></a>
       <h2 style="margin: 0; margin-bottom: 20px; color: #FF5722; text-align: center; font-size: 27px; position: absolute; top: 10px; right: 0; width: 100%;">'.$titulo.'</h2>
       <div href="#" style="position: absolute; width: 100px; height: 600px; right: 50px; top:108px"></div>
       <div style="border: 1px solid; margin: 30px 0; padding:20px; padding-bottom:5px; background-color:#F7BE81">
            <p style="line-height:10px"><strong>ENCUESTADO&nbsp;&nbsp;:</strong> '.($nombres).'</p>
            <p style="line-height:10px"><strong>FECHA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> '.$hoy.'</p>
       </div>
       '.$table.'
       </body>
   </html>';
$pdfObj->SetTitle("Encuesta");
$pdfObj->WriteHTML(utf8_encode($fondo));
// $pdfObj->Output("pdf1.pdf", 'D');
// $pdfObj->WriteHTML(utf8_encode($firma_docente));

?>

