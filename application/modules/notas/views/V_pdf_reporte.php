<?php

$width =  ($count <= 10) ?  '69%' : '100%';


$fondo =
'<!DOCTYPE html>
    <html>
        <head>
            <title>Reporte</title>
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
                    margin-left: 20px;
                    text-align:center;
                    float:right;
                    width:32%;
                    height:135px;
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

                .nota-asistencia h2{
                    padding:0;
                    margin:0;
                    text-align:center;
                    font-size:60px;
                    color: #757575;
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
                    width:'.$width.';
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
    
                .foto-perfil{
                    text-align:right;
                    float:left;
                    width: 40%;
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
                                <h1><strong>'.$nombre.'</strong></h1>
                                <p>'.$grado.'</p>
                                <p style="margin:0;padding:0;">18/19/2016</p>
                                <p><strong>Tercio Superior</strong></p>    
                            </div>
                        </div>        
                        <div class="mdl-card comportamiento">
                            <div class="nota-asistencia">
                                <h1>Nota</h1>
                                <div class="puesto">
                                    <h2>15</h2>
                                </div>
                            </div>                  
                        </div>
                    </div>
                    <div class="mdl-card calificaciones">
                        <div class="cuerpo-libreta">
                            <div id="notas">
                                '.utf8_encode($tabla).'
                            </div>
                        </div>
                        <br/>
                    </div>
                </div>
                      
                <div class="columna2">
                    <div class="mdl-card detalles">
                        <div class="graficos">
                            <div class="grafico2" style="margin-top:5px;">

                            </div>
                        </div>
                        <div class="meritos" style="height: 143px;">
                          
                        </div>
                        <div class="observaciones">
                            <h1>comentario</h1>
                            <div class="contenido-observacion">
                                <p></p>
                            </div>
                            <div class="imagen-observacion" style="text-align:center;margin-top:25px;width: 30%;float:right;">
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
    </html>';
$pdfObj->SetTitle("Reporte");
$pdfObj->WriteHTML(utf8_encode($fondo));
?>