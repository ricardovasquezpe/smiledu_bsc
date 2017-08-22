<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>  
        <title>Cuadro de Mando | <?php echo NAME_MODULO_BSC?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_BSC?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_BSC?>" />
            
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">  
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">   
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC?>css/submenu.css">
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PUBLIC_BSC?>css/shideEffect.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC?>css/main.css">
        
        <style>
            .modal-backdrop.fade.in{
            	height: 1200px !important;
            }
        </style>

    </head>
	<body onload="screenLoader(timeInit);">
	   <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
    		<?php echo $menu ?>      
            <main class='mdl-layout__content'>
                <section class="p-0">
                    <div class="mdl-content-cards"> 
                    <?php if(_getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_PROMOTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR_CALIDAD) { ?>
                        <div class="mdl-card" id="indicator-head">
                            <div class="mdl-card__supporting-text backCirc100 text-left"> 
                                <div class="mdl-graphic container-gauge linEst100" data-toggle="tooltip" data-title="Zona de riesgo: 31 / Valor Meta: 80" data-placement="bottom" ></div>
                                <div class="mdl-graphic-desc">
                                    <h2 class="mdl-card__title-text m-b-10">Avantgard</h2>
            				        <p>Misi&oacute;n: "Centrados en los</p>
            				        <p>aprendizajes y en el desarrollo</p>
            				        <p>de los talentos de nuestros</p>
            				        <p>estudiantes".</p>
                                </div>
                            </div>
                            <div id="grafico_barras"></div>
                            <div class="mdl-card__menu"  > 
                                <button id="menu-head" class="mdl-button mdl-js-button mdl-button--icon" >
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="menu-head">
                                    <li class="mdl-menu__item" onclick="openEditarValorAmarilloGeneral()"><i class="mdi mdi-edit"></i>Editar valor</li>
                                    <li class="mdl-menu__item" onclick="goToAllIndicadores()"><i class="mdi mdi-visibility"></i>Ver todos los Indicadores</li>
                                </ul>
                            </div>
                            <div id="colorBarraGeneral"></div>  
                        </div>
                        <div class="p-tb-16">               
                            <?php echo isset($lineasEstrat) ? $lineasEstrat : null;?>
                        </div>
                    <?php } else {?>
                        <div class="img-search" id="cont_search_empty7">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/magic_empty_state.png">
                            <p><strong>Hey!</strong></p>
                            <p>Prueba el buscador</p>
                            <p>m&aacute;gico.</p>
                        </div>
                    <?php } ?>    
                    </div>
                </section>
            </main>
        </div>
    	     		
	    <div class="offcanvas"></div>
	    
    	<form action="c_main/logout" name="logout" id="logout" method="post"></form>
        
    	<div class="modal fade" id="gaugesObjetivos" tabindex="-1" aria-labelledby="simpleModalLabel" aria-hidden="true">
	        <div class="modal-dialog modal-lg mdl-transparent">
                <div class="modal-content mdl-transparent">
                    <div class="mdl-card mdl-transparent">
                        <div class="mdl-card__title mdl-transparent">
    						<h2 class="mdl-card__title-text">Objetivos estrat&eacute;gicos</h2>
    					</div> 
    					<div class="mdl-card__supporting-text mdl-transparent"  id="gauges_objetivos"></div>
    				</div>
    				<div class="mdl-card__menu">
    				    <button class="mdl-button mdl-js-button mdl-button--icon" data-dismiss="modal"><i class="mdi mdi-close"></i></button>
				    </div>
	            </div>
    		</div>
    	</div>

    	<div class="modal fade backModal" id="modalEditarFlgAmarilloLinea" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar valores</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
					       <div class="row">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="contInputAmarilloLinea">
                                        <input class="mdl-textfield__input" type="text" id="valorAmarilloLinea" name="valorAmarilloLinea" maxlength="2">
                                        <label class="mdl-textfield__label" for="valorAmarilloLinea">Zona de riesgo</label>
                                    </div>
                                </div>
					            <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="contInputMeta_LE">
                                        <input class="mdl-textfield__input" type="text" id="valorMeta_LE" name="valorMeta_LE" maxlength="3">
                                        <label class="mdl-textfield__label" for="valorMeta_LE">Meta</label>
                                    </div>
                                </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonEVZ" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="editarValorZonaRiesgoLineaEst()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade backModal" id="modalEditarFlgAmarilloObjetivo" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar valores</h2>
    					</div>  
					    <div class="mdl-card__supporting-text">				
					       <div class="row">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-focused" id="contInputObjetivo">
                                        <input class="mdl-textfield__input" type="text" id="valorAmarilloObjetivo" name="valorAmarilloObjetivo" maxlength="2">        
                                        <label class="mdl-textfield__label" for="valorAmarilloObjetivo">Zona de riesgo</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="valorVerdeObjetivo" name="valorVerdeObjetivo" maxlength="3">        
                                        <label class="mdl-textfield__label" for="valorVerdeObjetivo">Meta</label>                            
                                    </div>
                                </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonEZR" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="editarValorZonRiesgoObjetivo()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade backModal" id="modalEditarFlgAmarilloGeneral" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar valores</h2>
    					</div>  
					    <div class="mdl-card__supporting-text">				
					       <div class="row">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-focused" id="contInputGeneral">
                                        <input class="mdl-textfield__input" type="text" id="valorAmarilloGeneral" name="valorAmarilloGeneral">        
                                        <label class="mdl-textfield__label" for="valorAmarilloGeneral">Ingrese valor</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-focused">
                                        <input class="mdl-textfield__input" type="text" id="valorVerdeGeneral" name="valorVerdeGeneral" disabled readonly="readonly">        
                                        <label class="mdl-textfield__label" for="valorVerdeGeneral">Valor Meta</label>                            
                                    </div>
                                </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="editarValorAmarilloGeneral()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        <form action="c_linea_estrategica/goToAllIndis" id="formGoToIndis" method="post"></form>
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts-more.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/exporting.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC?>js/jslineaestrategica.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC?>js/jsutilsbsc.js"></script>
        
        <script>
           init();
           initMain();
           imageMainHeader("icon_balance");
           
            <?php if(_getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_PROMOTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_SUBDIRECTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR_CALIDAD) {?>
                initMain();
            	setDivHeight();
            	$('.highcharts-yaxis-labels').find("text").css('font-size','7px');
            	$('.highcharts-tracker').find("tspan").css('font-size','15px');
            	$('.highcharts-tracker').find("tspan").css('fill','#959595');
            	
            	$('.highcharts-data-labels g rect').css('display','none');
            	$('.highcharts-container svg path[fill="transparent"]').css('fill','white');
            	$('.highcharts-series-group circle').css('fill','#959595');

            	$(".highcharts-yaxis-labels").find("text").find("tspan").append("%");
            <?php }if(_getSesion(BSC_ROL_SESS) == ID_ROL_ADMINISTRADOR){ ?>
            setTimeout(function(){ $(".mdl-layout__drawer").addClass("is-visible"); $(".mdl-layout__obfuscator").addClass("is-visible");}, 1000);
            <?php }?>
     		magicIcon();
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip(); 
            });  
        </script>        
	</body>
</html>