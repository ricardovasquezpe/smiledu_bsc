  <?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>      
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>    
        <title>Cuadro de Mando | <?php echo NAME_MODULO_PAGOS;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_PAGOS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_PAGOS;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>    
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/submenu.css">
        
	</head>

	<body onload="screenLoader(timeInit);">
	   
	    <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
    		<?php echo $menu ?>
    		<main class='mdl-layout__content'>
                <section>
                    <div class="mdl-content-cards">
                        <div class="row">
                            <div class="col-sm-6"> 
                                <div class="mdl-card">
                                    <div class="mdl-card__title ">
                                        <h2 class="mdl-card__title-text">Cobranza General</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text p-rl-0 p-t-0 br-b" id="container1">
                                        <div class="img-search">
                                            <img src="<?php echo base_url()?>public/general/img/smiledu_faces/filter_fab.png">
                                        </div>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" id="option_1">
                                            <i class="mdi mdi-more_vert"></i>
                                        </button>
                                        <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="option_1">
                                            <li class="mdl-menu__item" onclick="exrpotChartJPEG('#container1', 1);">
                                                <i class="mdi mdi-file_download"></i> Descargar PNG
                                            </li>
                                            <li class="mdl-menu__item" onclick="exrpotChartJPEG('#container1', 2);">
                                                <i class="mdi mdi-file_download"></i> Descargar JPEG
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                                
                            <div class="col-sm-6">
                                <div class="mdl-card">
                                    <div class="mdl-card__title ">
                                        <h2 class="mdl-card__title-text">Pagado vs Pendiente(Mes)</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text p-rl-0 p-t-0 br-b" id="container2">
                                        <div class="img-search">
                                            <img src="<?php echo base_url()?>public/general/img/smiledu_faces/filter_fab.png">
                                        </div>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" id="option2">
                                            <i class="mdi mdi-more_vert"></i>
                                        </button>
                                        <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="option2">
                                            <li class="mdl-menu__item" onclick="exrpotChartJPEG('#container1', 1);">
                                                <i class="mdi mdi-file_download"></i> Descargar PNG
                                            </li>
                                            <li class="mdl-menu__item" onclick="exrpotChartJPEG('#container1', 2);">
                                                <i class="mdi mdi-file_download"></i> Descargar JPEG
                                            </li>
                                        </ul>
                                    </div>
                                </div>                                
                            </div>                         
                                                       
                            <div class="col-sm-6">
                                <div class="mdl-card">
                                    <div class="mdl-card__title ">
                                        <h2 class="mdl-card__title-text">Pagado vs Pendiente(Sede)</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text p-rl-0 p-t-0 br-b" id="container3">
                                        <div class="img-search">
                                            <img src="<?php echo base_url()?>public/general/img/smiledu_faces/filter_fab.png">
                                        </div>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                            <i class="mdi mdi-more_vert"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="mdl-card">
                                    <div class="mdl-card__title ">
                                        <h2 class="mdl-card__title-text">Vencidos vs Puntuales vs Normal</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text p-rl-0 p-t-0 br-b" id="container4">
                                        <div class="img-search">
                                            <img src="<?php echo base_url()?>public/general/img/smiledu_faces/filter_fab.png">
                                        </div>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                            <i class="mdi mdi-more_vert"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-12">
                                <div class="mdl-card">
                                    <div class="mdl-card__title ">
                                        <h2 class="mdl-card__title-text">Conceptos</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text p-rl-0 p-t-0 br-b" id="container5">
                                        <div class="img-search">
                                            <img src="<?php echo base_url()?>public/general/img/smiledu_faces/filter_fab.png">
                                        </div>
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
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap">
	             <button class="mfb-component__button--main">
	             	<i class="mfb-component__main-icon--resting mdi mdi-add" ></i>
	             </button>
	             <button class="mfb-component__button--main" data-toggle="modal" data-target="#modalFiltroFechasGraficos" data-mfb-label="Filtrar">
	             	<i class="mfb-component__main-icon--active mdi mdi-filter_list" ></i>
	             </button>
		         <ul class="mfb-component__list">
			         <li>
				         <button class="mfb-component__button--child " id="main_save_multi" data-mfb-label="Reporte anual de pagos"data-toggle="modal" data-target="#modalExportarExcel" data-paquete-text="Reporte anual de pagos">
				         	<i class="mdi mdi-file_download"></i>
				         </button>
			         </li>
                 </ul>
    		 </li>
        </ul>
        
        <div class="modal fade" id="modalExportarExcel" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-0 p-l-0">    				
					       <div class="row-fluid">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <select id="selectSede" name="selectSede" class="form-group pickerButn" data-live-search="true">
					                   <?php echo $optSede;?>
                                   </select>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <select id="selectYears" name="selectYears" class="form-group pickerButn" data-live-search="true">
					                   <?php echo $optYears;?>
                                   </select>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect " data-dismiss="modal">Cerrar</button>
                            <button id="botonEE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised download" onclick="descargarExcelGerencial();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalFiltroFechasGraficos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content ">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtro</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-l-0">
					       <div class="row-fluid">
    					       <div class="col-sm-12 mdl-input-group">
                             		<div class="mdl-icon mdl-icon__button">
                                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconfecInicio">				                            
                                            <i class="mdi mdi-today"></i>
    		                            </button>
                                    </div>
                           			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="fecInicio">
                                       <label class="mdl-textfield__label" for="fecInicio">Fecha de inicio</label>
                                   </div>                                       
                         	   </div>
                         	   <div class="col-sm-12 mdl-input-group">
                             		<div class="mdl-icon mdl-icon__button">
                                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconfecFin">				                            
                                            <i class="mdi mdi-today"></i>
    		                            </button>
                                    </div>
                           			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="fecFin">
                                       <label class="mdl-textfield__label" for="fecFin">Fecha de fin</label>
                                   </div>                                       
                         	   </div>
                     	   </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect " data-dismiss="modal">Cerrar</button>
                            <button id="botonFF" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="getGraficosByFechas();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <form action="C_download_excel" method="post" id="formExcel">
            <input id="idSede" name="idSede" type="hidden">
            <input id="year"   name="year"   type="hidden">
        </form>
        
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
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>jquery-mask/jquery.mask.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jsmoduloGerencial.js"></script>
        
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts-more.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/exporting.js"></script>
        <script type="text/javascript">
            initGraficoBarraLineas(<?php echo $dataG1;?>);
            initGraficoLinea(<?php echo $dataG2;?>);
            initGraficoComparacion(<?php echo $dataG3;?>);
            initGraficoSpider(<?php echo $dataG4;?>);
            initChartConceptos(<?php echo $dataG5;?>);
            initButtonCalendarDaysMaxToday('fecInicio');
            initButtonCalendarDaysMaxToday('fecFin');
            initMaskInputs('fecFin', 'fecInicio');
            init();
           
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        	    $('.pickerButn').selectpicker('mobile');
        	} else {
        		$('.pickerButn').selectpicker();
        	}
        	
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip(); 
            });
        </script>
	</body>
</html>