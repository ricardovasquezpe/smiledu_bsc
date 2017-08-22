<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title>Reporte | <?php echo NAME_MODULO_NOTAS?></title>
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
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
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
    		
            <main class='mdl-layout__content '>
                <section class="mdl-layout__tab-panel is-active" id="tab-1">
                    <div class="mdl-content-cards" id="contTB" style="display:none">
                        <div class="mdl-card">
                            <div class="mdl-card__title" >
                                <h2 class="mdl-card__title-text" id="titleTb">Reportes</h2>
                            </div>                                    
                            <div class="mdl-card__supporting-text p-0 br-b"> 
                                <div id="contTbCursos" class="table-responsive"></div>
                                <div id="cont_tabla_reportes2" class="table-responsive" style="display:none"></div>    
                            </div>
                            <div class="mdl-card__menu iconsMenu">
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect aaa">
                                    <i class="mdi mdi-search"></i>
                                </button>
                             </div>                                                 
                            <div class="mdl-card__menu" id="change-table-graphic">
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" id="btn-reporte" style="display: none" onclick="changeView()">
                                    <i class="mdi mdi-grid_on"></i>
                                </button>                                      
                            </div>
                        </div>      
                    </div>
                </section>
            </main> 		
        </div>
                
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
                <button class="mfb-component__button--main" onclick="abrirCerrarModal('modalFiltro')" data-mfb-label="Filtrar">
                   <i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                   <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
                </button>
             </li>
        </ul>
        
        <div style="visibility:hidden;">
            <form action="c_reporte/generarPdf" method="post" id="formReporte">
                <input type="text" name="contTabla"  id="contTabla"  value="">
                <input type="text" name="contTipo"   id="contTipo"   value="">
                <input type="text" name="contCount"  id="contCount"  value="">
                <input type="text" name="nomAlumno"  id="nomAlumno"  value="">
                <input type="text" name="grado"      id="grado"  value="">
            </form>
        </div>
        
        <!-- Modals -->
        <div class="modal fade" id="modalAulas" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-rl-0">    				
					       <div class="row-fluid">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
					                   <select id="cmbYear" name="cmbYear" class="form-control pickerButn" data-live-search="true" title="Selec. A&ntilde;o">
                			                <option value="">Selec. A&ntilde;o</option>
                			                 <?php echo isset($cmbYears) ? $cmbYears : null; ?>        
                			           </select>
            			           </div>
					           </div>			           
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>    
        
         <!-- Modals -->        
        <div class="modal fade backModal" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Filtrar</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div class="row m-0 p-0">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only" for="selectTipoReporte">
                                    <select id="selectTipoReporte" name="selectTipoReporte" class="form-control selectTipoReporte" data-live-search="true"  data-noneSelectedText="Seleccione un a�o" onchange="getComboByTipoReporte()">
						                <option value="">Seleccione Reporte</option>
						                <?php echo isset($cmbReportes) ? $cmbReportes : null;?>
						            </select>
						        </div>
   								<div class="mdl-select" id="contCmbYear"></div>
					            <div class="mdl-select" id="contCmbSede"></div>
					            <div class="mdl-select" id="contCmbGrado"></div>
					            <div class="mdl-select" id="contCmbAula"></div>
					            <div class="mdl-select" id="contCmbBim"></div>
                        	</div>
                        <div class="mdl-card__actions">
                            <button id="botonFR" class="mdl-button mdl-button--colored mdl-js-button mdl-button--raised accept" type="button" data-dismiss="modal">Aceptar</button>
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
        <script src="<?php echo RUTA_PUBLIC_NOTAS?>js/jsReporte.js"></script>
        
        <script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/highstock.js"></script>
        <script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts-more.js"></script>
    	<script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/exporting.js"></script>
        <script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/heatmap.js"></script>
        
        <script type="text/javascript">
        init();   
        </script>   
	</body>
</html>