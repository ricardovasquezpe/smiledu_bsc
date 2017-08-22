<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Reportes | <?php echo NAME_MODULO_MATRICULA?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width; initial-scale=1, maximum-scale=1"> 
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_MATRICULA?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_MATRICULA?>" />
        
        <link rel="stylesheet" type="text/css" href="<?php echo RUTA_PLUGINS?>highcharts/">
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>wizard/css/wizard.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_MATRICULA?>css/submenu.css">
        
        <style type="text/css">
            .btn_opacity{
            	opacity: 0.4;
            }
            
            #grafico5 .highcharts-axis{
            	stroke: transparent !important;
            }
        </style>   
        
        <style type="text/css">
            .popover.bottom>.arrow:after {
            	border-bottom-color: #FF9200;
            }
            .popover{
            	padding: 0px;
            }
            .columns.columns-right.btn-group.pull-right{
            	margin: 0 !important
            }.classroom-link{
            	cursor: pointer;
            }
        </style>
    </head>
    
    <body>
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
        
    		<?php echo $menu ?>
             <div class="img-search" id="cont_filter_empty">
                <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                <p>Primero debemos filtrar para</p>
                <p>visualizar tus reportes.</p>                             
            </div>
           
            <main class='mdl-layout__content '>
                <section class="mdl-layout__tab-panel is-active" id="tab-1">
                    <div class="mdl-content-cards" id="cont_alumnos" style="display:none">
                        <div class="mdl-card">
                            <div class="mdl-card__title" >
                                <h2 class="mdl-card__title-text" id="titleTb">Reportes</h2>
                            </div>                                    
                            <div class="mdl-card__supporting-text p-0 br-b"> 
                                <div id="cont_tabla_reportes1" class="table-responsive"></div>
                                <div id="cont_tabla_reportes2" class="table-responsive" style="display:none"></div>    
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
        
        <div class="modal fade backModal" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Filtrar</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div class="row m-0 p-0">
                                <div class="col-xs-12 p-0 m-0 m-b-15 mdl-select" for="selectTipoReporte">
                                    <select id="selectTipoReporte" name="selectTipoReporte" class="form-control selectButton" data-live-search="true" data-container=".mdl-select[for='selectTipoReporte']" data-noneSelectedText="Seleccione un a�o" onchange="getComboByTipoReporte()">
						                <option value="">Seleccione Reporte</option>
						                <?php echo $comboReportes ?>
						            </select>
                                </div>
                                <div id="comboReportes"></div>
                                <div id="comboReportes_6"></div>
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <button id="botonFR" class="mdl-button mdl-button--colored mdl-js-button mdl-button--raised accept" type="button" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalVistaFamiliares" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:100;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Familiares</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-0 br-b">
                            <div id="cont_tabla_familiares_by_CodFam"></div>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-js-button mdl-button--icon" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalAlumnos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Alumnos</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-r-0 p-l-0 br-b">
                            <div id="cont_tabla_AlumnosAula"></div>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-js-button mdl-button--icon" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalDocentes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Docentes</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-r-0 p-l-0 br-b">
                            <div id="cont_tabla_DocentesAula"></div>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-js-button mdl-button--icon" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalHijos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Hijos</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-r-0 p-l-0 br-b">
                            <div id="cont_tabla_DocentesAula"></div>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-js-button mdl-button--icon" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>
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
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>velocity/js/velocity.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
    	<script src="<?php echo RUTA_JS?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_PUBLIC_MATRICULA?>js/jsreportes.js"></script>
                
    	<script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts.js"></script>
    	<script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts-more.js"></script>
    	<script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/exporting.js"></script>
    	<script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/export-csv.js"></script>
    	
    
        <script type="text/javascript">
            $('main.mdl-layout__content').addClass('is-visible');
            init();
            $(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip(); 
            });
        </script>
    </body>
</html>