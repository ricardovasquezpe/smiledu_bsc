<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>   
        <title>Tutoria | <?php echo NAME_MODULO_NOTAS?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_NOTAS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_NOTAS;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>highcharts/">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css"> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_NOTAS?>css/submenu.css">     
        
        <style type="text/css">
            table img.img-circle{
                height: 20px;
            	width: 20px;
                float: left;
            	margin-right: 5px
            }
        </style>
	</head>

	<body onload="screenLoader(timeInit);">
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>    		
    		<?php echo $menu ?>   		
            <main class='mdl-layout__content'>
                <section>
                    <div class="mdl-content-cards">                                
                        <div class="row-fluid">
                            <div class="col-md-3 col-sm-4">
                                <div id="taulas" class="mdl-card">
                                    <div class="mdl-card__title">
                                        <h2 id="Tutor" class="mdl-card__title-text">Aulas de tutor&iacute;a</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text br-b p-0">
                                        <?php echo $tablaAulas?>
                                    </div>
                                </div>                                    
                                <div id="container3" style="display: none" ></div>
                                <img id="binary"  name="binary" style="display: none">
                            </div>
                            <div id="container1" style="display: none"></div>
                            <div id="container2" style="display: none"></div>
                            <div id="container4" style="display: none"></div>
                            <div class="col-md-9 col-sm-8">
                                <div id="tAlumnos" class="mdl-card" style="display : none">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text" id="titleTb">Estudiantes</h2>
                                    </div>
                                    <div >
                                        <div class="mdl-card__supporting-text br-b p-0" id="contTbAlumnos"></div>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                            <i class="mdi mdi-search"></i>
                                        </button>
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                            <i class="mdi mdi-print"></i>
                                        </button>
                                        <button id="opc_docente-'.$val.'" class="mdl-button mdl-js-button mdl-button--icon" >
                                            <i class="mdi mdi-more_vert"></i>
                                        </button>
                                        <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="opc_docente-'.$val.'">
                                            <li class="mdl-menu__item" onclick="getGraficosAula()"><i class="mdi mdi-public"></i>Graficos</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>                                   
                            <div class="col-md-9 col-sm-8">
                                <div id="tDocentes" class="mdl-card" style="display : none">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text" id="titleTb2">Docentes</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text br-b p-0" id="contTbDocentes"></div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                            <i class="mdi mdi-search"></i>
                                        </button>
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                            <i class="mdi mdi-print"></i>
                                        </button>
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                            <i class="mdi mdi-more_vert"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>                                         
                        </div>
                    </div>
                </section>
            </main>	
        </div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
                 <button class="mfb-component__button--main" data-toggle="modal" data-target="#modalLibreta" data-mfb-label="Generar libreta">
                     <i class="mfb-component__main-icon--resting mdi mdi-insert_drive_file"></i>
                     <i class="mfb-component__main-icon--active  mdi mdi-insert_drive_file"></i>
                 </button>
             </li>
        </ul>
        
        <div class="modal fade" id="modalBimestre" data-live-search="true" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Calificaciones</h2>
    					</div>
					    <div id="contCursos" class="mdl-card__supporting-text p-0 p-t-20 p-b-20"></div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalDetalleNotas" data-live-search="true" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Detalle de Notas</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0 p-t-20 p-b-20" id="contTbDetalleNotas"></div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalGrafico" data-live-search="true" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card">
    					<div class="mdl-card__supporting-text">
    					    <div id="contGrafico"></div>
        					<div id="contGraficoAsistenciaAula"></div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalGraficoAlumno" data-live-search="true" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" align="center">Promedio por Bimestre</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					    <div id="contGraficAlumnos"></div>
        					<div id="contGraficAsistencia"></div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Enviar Mensaje</h2>   
                        </div>        
                        <div class="mdl-card__supporting-text p-l-0 p-r-0">
                            <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    			                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    			                   <input class="mdl-textfield__input" type="text" id="correoDocente">
    			                   <label class="mdl-textfield__label">Correo:</label>                                  
                                </div>
                            </div>
                            <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="descCurso" name="descCurso" maxlength="80">
                                    <label class="mdl-textfield__label" for="descCurso">Asunto</label>
                                </div>
                            </div>
                            <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <textarea class="mdl-textfield__input" id="message-text"  maxlength="125" rows="4"></textarea>
                                    <label class="mdl-textfield__label">Mensaje:</label>
                                    <span class="mdl-textfield__limit" for="message-text" data-limit="120"></span>  
                                </div>
                            </div>
                        </div> 
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonEM" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised send" onchange="iniTableCursoGrado"  onclick="asignarCursoUgel()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalElegirBimestre" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Seleccione Bimestre</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-l-0 p-r-0">
                            <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                              <div class="row-fluid">
                    	           <div id="contListaTuCo" class="text-center">
                                       <img id="icon1"  class="mdl-list__item-avatar" onclick="selecBimestre(1)" src="<?php echo base_url()?>/public/modulos/notas/img/bimestre1.png">
                                       <img id="icon2"  class="mdl-list__item-avatar" onclick="selecBimestre(2)" src="<?php echo base_url()?>/public/modulos/notas/img/bimestre2.png">
                                       <img id="icon3"  class="mdl-list__item-avatar" onclick="selecBimestre(3)" src="<?php echo base_url()?>/public/modulos/notas/img/bimestre3.png">
                                       <img id="icon4"  class="mdl-list__item-avatar" onclick="selecBimestre(4)" src="<?php echo base_url()?>/public/modulos/notas/img/bimestre4.png">                                           
                                       <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                           <div id="coment" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="display:none">
                                               <textarea class="mdl-textfield__input" id="textComentario"  maxlength="125" rows="4"></textarea>
                                               <label class="mdl-textfield__label">Comentario:</label>    
                                               <span class="mdl-textfield__limit" for="textComentario" data-limit="120"></span>             
                                           </div>
                                       </div>
                    	           </div>
                    	      </div>                       	      
                            </div>
                        </div>
                        <div class="mdl-card__actions">      
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonEB" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised download" disabled onclick="generarLibreta()">Descargar</button>
                        </div>
                            <!-- <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                              <div class="mdl-tabs__tab-bar">
                                  <a href="#icon1" class="mdl-tabs__tab is-active"><img src="<?php echo base_url()?>/public/modulos/notas/img/nro_1.jpg"></a>
                                  <a href="#icon2" class="mdl-tabs__tab"><img src="<?php echo base_url()?>/public/modulos/notas/img/nro_1.jpg"></a>
                                  <a href="#icon3" class="mdl-tabs__tab"><img src="<?php echo base_url()?>/public/modulos/notas/img/nro_1.jpg"></a>
                                  <a href="#icon4" class="mdl-tabs__tab"><img src="<?php echo base_url()?>/public/modulos/notas/img/nro_1.jpg"></a>
                              </div>
                            
                              <div class="mdl-tabs__panel is-active" id="icon1">
                                <div id="coment" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                   <label class="mdl-textfield">Comentario:</label>
                                   <textarea class="mdl-textfield__input" id="textComentario"></textarea>        
                               </div>  
                              </div>
                              <div class="mdl-tabs__panel" id="icon2">
                                <div id="coment" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                   <label class="mdl-textfield">Comentario:</label>
                                   <textarea class="mdl-textfield__input" id="textComentario"></textarea>        
                               </div>  
                              </div>
                              <div class="mdl-tabs__panel" id="icon3">
                                <div id="coment" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                   <label class="mdl-textfield">Comentario:</label>
                                   <textarea class="mdl-textfield__input" id="textComentario"></textarea>        
                               </div>
                              </div>
                              <div class="mdl-tabs__panel" id="icon4">
                                <div id="coment" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                   <label class="mdl-textfield">Comentario:</label>
                                   <textarea class="mdl-textfield__input" id="textComentario"></textarea>        
                               </div> 
                              </div>
                            </div>
                        </div>-->                      
                </div>
            </div>     
        </div>
    </div>
    <div  style="visibility: hidden;background-color: #E5E6E6">
        <form action="c_tutoria/generar_libreta" method="post" id="formLibreta">
            <input type="text" name="idAlumno"       id="idAlumno"       value="">
            <input type="text" name="idMain"         id="idMainGlobal"   value="">
            <input type="text" name="idAnio"         id="idAnio"         value="">
            <input type="text" name="bimestre"       id="bimestre"       value="">
            <input type="text" name="idAula"         id="idAula"         value="">
            <input type="text" name="comentario"     id="comentario"     value="">
            <input type="text" name="imagenGrafico1" id="imagenGrafico1" value="">
            <input type="text" name="imagenGrafico2" id="imagenGrafico2" value="">
            <input type="text" name="imagenGrafico3" id="imagenGrafico3" value="">
        </form>
    </div>
               
        
           	       
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>  
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_NOTAS?>js/jsTutoria.js"></script>
        <script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/highstock.js"></script>
        <script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts-more.js"></script>
    	<script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/exporting.js"></script>
        <script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/heatmap.js"></script>
        
        <script>
        	(function (H) {
        	    H.Chart.prototype.createCanvas = function (divId){
        	        var svg    = this.getSVG(),
        	            width  = parseInt(svg.match(/width="([0-9]+)"/)[1]),
        	            height = parseInt(svg.match(/height="([0-9]+)"/)[1]),
        	            canvas = document.createElement('canvas');
        	        canvas.setAttribute('width', width);
        	        canvas.setAttribute('height', height);
        	        if(canvas.getContext && canvas.getContext('2d')){
        	            canvg(canvas, svg);
        	            return canvas.toDataURL("image/jpeg");
        	        }else{
        	            alert("Tu navegador no soporta esta funcionalidad, porfavor actualiza tu navegador");
        	            return false;
        	        }
        	    }
        	}(Highcharts)); 
        	init();      
        </script>   
	</body>
</html>