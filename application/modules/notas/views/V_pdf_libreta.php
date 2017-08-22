<?php
$zonahoraria = date_default_timezone_get();
ini_set('date.timezone','America/Lima');
$hoy = date("Y-m-d H:i:s");

$nombre1 = __generateRandomString(15).'.png';
$nombre2 = __generateRandomString(15).'.png';
$nombre3 = __generateRandomString(15).'.png';


$file1 = $_SERVER['DOCUMENT_ROOT'].'/smiledu/uploads/modulos/notas/'.$nombre1;
file_put_contents($file1, base64_decode($data1[1]));
//Segunda Imagen
$file2 = $_SERVER['DOCUMENT_ROOT'].'/smiledu/uploads/modulos/notas/'.$nombre2;
file_put_contents($file2, base64_decode($data2[1]));
//Tercer Imagen
//$imgOutput = $this->base64_to_jpeg($img3, 'imagen_nota3.jpg');
$file3 = $_SERVER['DOCUMENT_ROOT'].'/smiledu/uploads/modulos/notas/'.$nombre3;
file_put_contents($file3, base64_decode($data3[1]));


if($promMerito > 11.5) {
    $imgCarita = '<img src="'.RUTA_PUBLIC_NOTAS.'img/aprobado.jpg" style="height: 70px;width:70px;">';
} else {
    $imgCarita = '<img src="'.RUTA_PUBLIC_NOTAS.'img/estudiar.jpg" style="height: 70px;width:70px;">';
}


if($promMerito > 11.5) {
    $imgFlecha = '<img src="'.RUTA_PUBLIC_NOTAS.'img/flecha_arriba.png" style="height: 30px;width:25px;">';
}

else  {
    $imgFlecha = '<img src="'.RUTA_PUBLIC_NOTAS.'img/flecha_abajo.png" style="height: 30px;width:25px;">';
}

if($awardsEstuPosi == '' || $awardsEstuNegat == '') {
    $awards = ' <div class="psicologia" style="margin-top:10px;">
                            <div class="lomejor"> 
                               '.$awardsEstuPosi.'
                            </div>                
                            <div class="lomejor"> 
                                '.$awardsEstuNegat.'    
                             </div>
                        </div>';    
}
else {
    $awards = ' <div class="psicologia" style="margin-top:10px;">
                            <div class="lobueno">
                               '.$awardsEstuPosi.'
                            </div>
                            <div class="lomalo">
                                '.$awardsEstuNegat.'
                             </div>
                        </div>';
}

$fondo = 
'<!DOCTYPE html>
    <html>
        <head>
            <title>Libreta de Notas</title>               
            <style>
                body{
                    padding:0;
                    margin:0;
                    width:100%;
                    font-family: "Roboto", "Helvetica", "Arial", sans-serif;
                    background: rgba(223,225,225,1);
                    background: -moz-linear-gradient(top, rgba(223,225,225,1) 0%, rgba(223,225,225,1) 60%, rgba(255,105,5,1) 60%, rgba(255,108,10,1) 60%, rgba(255,151,77,1) 60%, rgba(255,134,41,1) 100%);
                    background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(223,225,225,1)), color-stop(55%, rgba(223,225,225,1)), color-stop(60%, rgba(255,105,5,1)), color-stop(60%, rgba(255,108,10,1)), color-stop(60%, rgba(255,151,77,1)), color-stop(100%, rgba(255,134,41,1)));
                    background: -webkit-linear-gradient(top, rgba(223,225,225,1) 0%, rgba(223,225,225,1) 60%, rgba(255,105,5,1) 60%, rgba(255,108,10,1) 60%, rgba(255,151,77,1) 60%, rgba(255,134,41,1) 100%);
                    background: -o-linear-gradient(top, rgba(223,225,225,1) 0%, rgba(223,225,225,1) 60%, rgba(255,105,5,1) 60%, rgba(255,108,10,1) 60%, rgba(255,151,77,1) 60%, rgba(255,134,41,1) 100%);
                    background: -ms-linear-gradient(top, rgba(223,225,225,1) 0%, rgba(223,225,225,1) 60%, rgba(255,105,5,1) 60%, rgba(255,108,10,1) 60%, rgba(255,151,77,1) 60%, rgba(255,134,41,1) 100%);
                    background: linear-gradient(to bottom, rgba(223,225,225,1) 0%, rgba(223,225,225,1) 60%, rgba(255,105,5,1) 60%, rgba(255,108,10,1) 60%, rgba(255,151,77,1) 60%, rgba(255,134,41,1) 100%);
                }

                .mdl-card{
                    border-radius: 10px;
                    background-color: #fff;
                    min-height: 100px;
                    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px rgba(0, 0, 0, .2), 0 1px 5px 0 rgba(0, 0, 0, .12);
                    box-sizing: border-box;
                }
        
                .logo-institucion{
                    width: 13%;
                    height: 135px;
                    float:left;
                    text-align:center;
                }
        
                .informacion{
                    margin-left: 12px;
                    text-align:center;
                    float:left;
                    width:32%; 
                    height: 135px;
                }
    
                .comportamiento{
                    width:49%;
                    float:right;
                    height: 130px;
                }
                
                .nota-asistencia{
                    text-align:center;
                    float:left;
                    width: 28%;

                }
                
                .puesto{
                    border-right: 1px dotted #757575;
                }
                
                .detalle-asistencia{
                    float:right;
                    width: 70%;
                }
                
                .nota-asistencia h2{
                    padding:0;
                    margin:0;
                    text-align:center;
                    font-size:60px;
                    color: #757575;
                }
                
                .grafica-asistencia{
                    margin:0;
                    padding:0;
                    margin-top:0;
                    text-align: center;
                    float: left;
                    width: 50%;
                }
                
                .puntos-asistencia{
                    float: right;
                    width: 40%;
                    margin-top:25px;
                }
                
                .puntos-asistencia p{
                    color:#757575;
                    line-height: 0px;
                    padding:0;
                    margin: 5px 0;
                }

                .foto-perfil{
                    text-align:center;
                    float:left;
                    width: 40%;
                }
    
                .alumno-puesto{
                    background-color:#FF862A;
                    color:#fff;
                    width: 40px;
                    height:33px;
                    border-radius:50%;
                    margin-top:-35px;
                    margin-left:80px;
                    z-index:100;
                    font-size:20px;
                    padding-top:7px;
                }
    
                .foto-perfil span{
                    margin-top:5px;
                }
            
                .datos-perfil{
                    text-align: left;
                    float:right;
                    width: 58%;
                    padding-top:5px;
                    color:#757575;
                }
            
                .datos-perfil h1{
                    font-size: 15px;
                }
            
                .datos-perfil p{
                    line-height:4px;
                }
            
                .calificaciones{
                    margin-top:12px;
                    height: 902px;
                }
            
                table{
                    border-spacing:0;
                    border-collapse: collapse;             
                }
                         
                .columna1{
                    padding-right: 12px;
                    float:left;
                    width:69%;
                }
            
                .columna2{   
                    float:left;
                    width: 30%;
                }
            
                .observaciones{
                    padding: 20px;
                    margin-top: 40px;
                    background-color: #F9F9F9;
                    height: 138px;
                    border-top: 1px solid #BDBDBD;
                    border-bottom-left-radius: 10px;
                    border-bottom-right-radius:10px;
                }
            
                .observaciones h1{
                    padding: 3px 0;
                    font-weight: 100;
                    background-color: #fff;
                    text-align: center;
                    text-transform: uppercase;
                    font-size: 16px;
                    color: #757575;
                    border: 1px solid #BDBDBD;
                    border-radius: 50%;
                    width: 180px;
                    margin-top:-35px;
                    z-index:1000;
                }

                .contenido-observacion{
                    width:70%;
                    float: left; 
                    color:#757575;   
                }
            
                imagen-observacion img{
                    text-align:center;
                }
                               
                .psicologia,        
                .comportamiento{
                    padding: 2px 20px;
                }
            
                .graficos h1,
                .comportamiento h1{
                    font-size: 16px;
                    color: #757575; 
                }
                
                .graficos h1{
                    padding-left:15px;
                }
            
                .cuerpo-libreta table{
                    width:100%;
                }
                 
                .cuerpo-libreta table td{
                    text-align:center;
                    font-size:9pt;
                    padding:2mm 0;
                    border-right: none;
                    border-top: none;  
                }
            
                .firmas{
                    padding:10px 0;
                    margin-top: 50px; 
                    width: 100%; 
                    text-align: center;
                    z-index:1000;
                }
            
                .detalles{
                    height: 1050px;    
                }
            
                #notas table{
                    margin-top:10px;
                }
                
                #notas table td{
                    border-bottom: 1px solid #ccc;           
                }
                 
                .promedio{
                    margin-top: 50px;
                    margin-bottom:0;
                    margin-left: 30px;
                    text-align:center;
                }
        
                .promedio p{
                    color: #757575;
                }
            
                .promedio strong{
                    font-size: 40px;
                }
                
                .lobueno{
                    padding-right:10px;
                    border-right: 1px dashed #CCCCCC;
                    color: #757575;
                    text-align:center;
                    float:left;
                    width:47%;
                }

                .lomalo{
                    color: #757575;
                    text-align:center;  
                    float:right;
                    width:47%;
                } 

                .lomejor{ 
                    margin-left:100px;                   
                    width:50%;
                    text-align:center;
                }
    
                .painRow {
                     background: #FE9A2E;

                }
            </style>
        </head>
        <body>
            <div class="header-libreta"></div>
            <div id="libreta" style="padding:10px;">
                <div class="columna1">
                    <div class="contenido">
                        <div class="mdl-card logo-institucion">
                            <a href="http://smiledu.pe/" target="_blank"><img src="'.RUTA_IMG.'logos_colegio/avantgard.png" title="logo" style="height: 105px;width:105px; margin-top:15px;"></a>           
                        </div>                                
                        <div class="mdl-card informacion">
                            <div class="foto-perfil">
                                 <img src="'.RUTA_IMG.'foto_perfil_default.png" style="height: 100px;width:100px;margin-top: 18px;"> 
                                 <div class="alumno-puesto">10</div>           
                            </div>
                            <div class="datos-perfil">
                                <h1><strong>'.$alumno.'</strong></h1>
                                <p>'.$aula_grado.'</p>
                                <p style="margin:0;padding:0;">'.$hoy.'</p>
                                <p><strong>Tercio Superior</strong></p>    
                            </div>
                        </div>
                        <div class="mdl-card comportamiento"> 
                            <div class="nota-asistencia">
                                <h1>Nota de asistencia</h1>
                                <div class="puesto">
                                    <h2>'.$notaAsistencia.'</h2>
                                </div>
                            </div>
                            <div class="detalle-asistencia">
                                <div class="grafica-asistencia">
                                    <img style="width:100%; height:130px; margin:0; padding:0;" src="'.$file3.'">
                                </div>
                                <div class="puntos-asistencia">                                     
                                    <p><img src="'.RUTA_PUBLIC_NOTAS.'img/circle_rojo.png" style="height: 15px;width:15px;">&nbsp;&nbsp;Faltas</p>
                                    <p><img src="'.RUTA_PUBLIC_NOTAS.'img/circle_anaranjado.png" style="height: 15px;width:15px;">&nbsp;&nbsp;Faltas Just.</p>      
                                    <p><img src="'.RUTA_PUBLIC_NOTAS.'img/circle_amarillo.png" style="height: 15px;width:15px;">&nbsp;&nbsp;Tardanzas</p>
                                    <p><img src="'.RUTA_PUBLIC_NOTAS.'img/circle_verde.png" style="height: 15px;width:15px;">&nbsp;&nbsp;Tardanzas Just.</p>     
                                </div>    
                            </div>       
                        </div>
                    </div>     
                    <div class="mdl-card calificaciones">              
                        <div class="cuerpo-libreta">
                            <div id="notas">
                                '.$libreta_html.'
                            </div>                                              
                        </div>
                        <br/>
                        <div class="firmas">                 
                            <div style="width: 300px; float: left; margin-left: 100px;">
                                <div style="border-top: 2px solid #757575; padding-top: 5px; text-align: center;color:#757575;">Firma del tutor</div>
                            </div>  
                            <div style="width: 300px; margin-right: 100px; float: right">
                                <div style="border-top: 2px solid #757575; padding-top: 5px; text-align: center;color:#757575;">Firma del subdirector</div>
                            </div>
                        </div>
                    </div>                     
                </div>
                                           
                <div class="columna2">
                    <div class="mdl-card detalles">
                        <div class="graficos">                    
                            <div class="grafico1" style="margin-top:2px;">
                               <h1>Promedio por Bimestre</h1>         
                               <img style="width:95%; height: 60%;padding-left:5px;" src="'.$file1.'">
                            </div>
                            <div class="grafico2" style="margin-top:5px;">
                               <h1>Promedio por Curso</h1>  
                               <img style="width:95%; height: 70%;padding-left:5px;" src="'.$file2.'">
                            </div>                
                        </div>
                        <div class="meritos" style="height: 143px;">           
                            '.$awards.'
                        </div>
                        <div class="observaciones">                                         
                            <h1>Observaciones</h1>
                            <div class="contenido-observacion">
                                <p>'.$comentario.'</p>        
                            </div>
                            <div class="imagen-observacion" style="text-align:center;margin-top:25px;width: 30%;float:right;">
                                '.$imgCarita.'
                            </div>          
                        </div>
                    </div>                                   
                </div>
            </div>                                                              
        </body>
    </html>';   
$pdfObj->SetTitle("Libreta de notas");
$pdfObj->WriteHTML(utf8_encode($fondo));
unlink($file1);
unlink($file2);
unlink($file3);
//$pdfObj->WriteHTML(utf8_encode($firma_docente));
?>



