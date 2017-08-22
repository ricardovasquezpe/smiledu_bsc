<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Aula | <?php echo NAME_MODULO_MATRICULA?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_MATRICULA?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_MATRICULA?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">		
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_MATRICULA?>css/submenu.css">
	        
        <style type="text/css">          
            .mdl-layout__tab-bar-container{
            	display: none
            }
        </style>
    </head>
    
    <body>
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >        
    		<?php echo $menu ?>
            <main class='mdl-layout__content' onscroll="onScrollEvent(this)">
                <section id="tab-1">
                    <div class="mdl-content-cards"> 
                        <ol class="breadcrumb" id="breadCrumbEst" style="display: none">Filtrar:
                            <li class=""></li>
                            <li class=""></li>
                            <li class=""></li>
            			</ol>                    
                        <div id="cont_tabla_aulas" >
                        </div>
                        <div class="img-search" id="cont_search_empty">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/simple_search.png">
                                <p><strong>&#161;Hey!</strong></p>
                                <p>Prueba el buscador.</p>
                        </div>
                        <div class="img-search" id="cont_search_not_found" style="display: none;">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/not_filter_fab.png">
                            <p><strong>&#161;Ups!</strong></p>
                            <p>Tu filtro no ha sido</p>
                            <p>encontrado.</p>
                            <button class="mdl-button mdl-js-button mdl-button--raised" onclick="reintentarBusqueda();">Reintentar</button>
                        </div>
                        <div class="img-search" id="cont_search_not_found_letter" style="display: none;">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/magic_not_found.png">
                            <p><strong>&#161;Ups!</strong></p>
                            <p>No se encontrar&oacute;n</p>
                            <p>resultados.</p>
                        </div>
                        <div class="mdl-spinner__position" id="loading_cards" style="display: none;">
                            <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect">
                                <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active"></div>
                            </button>   
                        </div>
                    </div>
                </section>   
            </main>
        </div>
                
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap">
                <button class="mfb-component__button--main" >
                    <i class="mfb-component__main-icon--resting mdi mdi-add"></i>
                </button>
                <button class="mfb-component__button--main" onclick="goToCreateAula()" data-mfb-label="Nueva Aula">
                    <i class="mfb-component__main-icon--active mdi mdi-new_classroom"></i>
                </button>
                <ul class="mfb-component__list">
                    <li>
                        <button class="mfb-component__button--child" data-mfb-label="Aulas sin informaci&oacute;n" onclick="getAulasPendientes()">
                            <i class="mfb-component__child-icon mdi mdi-classsroom_incomplete"></i>
                        </button>
                    </li>  
                    <li>
                        <button class="mfb-component__button--child" data-mfb-label="Filtrar" onclick="abrirCerrarModal('modalFiltro');">
                            <i class="mfb-component__child-icon mdi mdi-filter_list"></i>
                        </button>
                    </li>                
                </ul>  
            </li>
        </ul>
        
        <div class="modal fade backModal" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtros</h2>
    					</div>
    					<div class="mdl-card__supporting-text ">
                            <div class="row">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="selectYearFiltroAulas" name="selectYearFiltroAulas" class="form-control selectButton" data-live-search="true" onchange="getSedesByYear('selectYearFiltroAulas','selectSedeFiltroAulas','selectGradoNivelFiltroAulas')">
    						                <option value="">Seleccione A&ntilde;o</option>
    						                <?php echo $comboYear ?>
    						            </select>
						            </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="selectSedeFiltroAulas" name="selectSedeFiltroAulas" class="form-control selectButton" data-live-search="true" onchange="getGradoNivelBySedeYear('selectYearFiltroAulas','selectSedeFiltroAulas','selectGradoNivelFiltroAulas')">
    						                 <option value="">Seleccione Sede</option>
    						             </select>
						             </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="selectGradoNivelFiltroAulas" name="selectGradoNivelFiltroAulas" class="form-control selectButton" data-live-search="true" onchange="getAulasByGradoNivelSedeYear('selectYearFiltroAulas','selectSedeFiltroAulas','selectGradoNivelFiltroAulas')">
    						                <option value="">Seleccione Grado y Nivel</option>
    						             </select>
						             </div>
                                </div>
                            </div>            
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonFL" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade backModal" id="modalAlumnos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content b-o">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Estudiantes</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-r-0 p-l-0">
                            <div id="cont_tabla_AlumnosAula"></div>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-js-button mdl-button--icon" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>   
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>               
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalConfirmarEliminarAula" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title p-b-0">
    						<h2 class="mdl-card__title-text" id="msjConfirmaEliminar"></h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <small>Al eliminar esta aula ya no se podr&aacute; ver nuevamente, ni registrar estudiantes.</small>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="eliminarAula();">Eliminar</button>
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
    	<script src="<?php echo RUTA_PUBLIC_MATRICULA?>js/jsaula.js"></script>
    	
        <script type="text/javascript">
            $('main.mdl-layout__content').addClass('is-visible');
            init();
        </script>
    </body>
</html>