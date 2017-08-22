<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title>Asistencia | <?php echo NAME_MODULO_NOTAS?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_NOTAS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_NOTAS;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>highcharts/">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
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
    	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">    		
    		<?php echo $menu ?>   		
            <main class='mdl-layout__content'>
                <section >
                    <div class="mdl-content-cards">                               
                        <div class="row-fluid">
                            <div class="col-sm-4">
                                <div id="tAsistencia" class="mdl-card">
                                    <div class="mdl-card__title">
                                        <h2 id="Tutor" class="mdl-card__title-text">Asistencia</h2>
                                    </div>
                                    <div id="contTbAsistencia" class="mdl-card__supporting-text br-b p-0">
                                        <?php echo $tablaAsistencia?>
                                    </div>
                                </div>
                                <div id="container1" style="display: none"></div>
                                <div id="container2" style="display: none"></div>
                                <div id="container3" style="display: none" ></div>
                                <img id="binary"  name="binary" style="display: none">
                            </div>
                                                
                            <div class="col-sm-8">
                                <div id="tCalifAsist" class="mdl-card">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text">Calificaci&oacute;n de asistencia</h2>
                                    </div>
                                     <div id="contTbAsistenciaCalif" class="mdl-card__supporting-text br-b p-0">  
                                        <?php echo $tablaselectAsistenciaCalif?>
                                    </div>
                                    <div class="mdl-card__menu">
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
        
        <div class="modal fade" id="mdAsignarPeso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar Peso</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-rl-0">    				
					       <div class="row-fluid">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					                   <label for="peso "class="mdl-text__label">Peso</label>
                                       <input id="peso" name="peso" class="mdl-textfield__input" type="number">
                                   </div>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnAP" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="agregarPeso()"></button>
                        </div>
                    </div>
                </div>
            </div>     
        </div> 
        
        <div class="modal fade" id="mdLimiteCalificacion" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                        	<h2 class="mdl-card__title-text">Agregar</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-rl-0">    				
                            <div class="row-fluid">					           
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <label for="limite "class="mdl-text__label">L&iacute;mite</label>
                                        <input id="limite" name="limite" class="mdl-textfield__input" type="number">
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only p-t-20">
                                    <div class="mdl-select">
                                        <select id="cmbNotaAlf" name="cmbNotaAlf" class="form-control pickerButn" data-live-search="true" title="Selec. A&ntilde;o">
                                           <option value="">Selec. Nota</option>
                                           <option value="A">A</option>
                                           <option value="B">B</option>
                                           <option value="C">C</option>
                                           <option value="D">D</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <label for="notaNum "class="mdl-text__label">Nota Num&eacute;rica</label>
                                        <input id="notaNum" name="notaNum" class="mdl-textfield__input" type="number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnLC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="agregarPesoCalificacion()"></button>
                        </div>
                    </div>
                </div>
            </div>     
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
        <script src="<?php echo RUTA_PUBLIC_NOTAS?>js/jsPesos_asistencia.js"></script>
        
        <script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/highstock.js"></script>
        <script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts-more.js"></script>
    	<script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/exporting.js"></script>
        <script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/heatmap.js"></script>
        
        <script>
            initPesosAsistencia();
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
        </script>   
	</body>
</html>