<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Seguimiento | <?php echo NAME_MODULO_SENC;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SENC?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_SENC;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC;?>css/submenu.css">
        
    </head>
    <body>
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		<?php echo $menu ?>
        	<main class='mdl-layout__content is-visible'>
                <section>
                    <div class="mdl-content-cards">
                        <div class="img-search" id="divEmptySte" style="display: block;">
                            <img src="<?php echo RUTA_IMG;?>smiledu_faces/filter_fab.png">
                            <p>Busca la encuesta EFQM a la que quieres darle seguimiento</p>
                        </div>
                    
                        <div class="cards" style="display: none;">
                            <div class="col-md-5">
                                <div class="mdl-card">
                                    <div class="mdl-card__title p-r-50">
                                		<h2 class="mdl-card__title-text titulo_encuesta_sedes"></h2>
                                	</div>
                                	<div class="mdl-card__supporting-text p-0 br-b">
                                	    <div id="contTbSedes" class="form floating-label table_distance">
                                	    </div>
                                	</div>
                                	<div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"style="top:5px" onclick="refreshSedes();">
                                            <i class="mdi mdi-refresh"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="mdl-card">
                                    <div class="mdl-card__title">
                                		<h2 class="mdl-card__title-text detalle_card2">&Aacute;reas</h2>
                                	</div>
                                	<div class="mdl-card__supporting-text p-0 br-b">
                                	    <div id="contTbAulas" class="form floating-label table_distance">
                                	         
                                	    </div>
                                	    <div class="empty_state_img" style="display:block;">
                                            <div class="img-search">
                                                <img src="<?php echo RUTA_IMG;?>smiledu_faces/simple_search.png">
                                                <p>Primero debes seleccionar</p>
                                                <p>una sede.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button id="btnRefreshDeta" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"style="top:5px">
                                            <i class="mdi mdi-refresh"></i>
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
    			<button  class="mfb-component__button--main" onclick="openModalPickEncuestaEFQM();" data-mfb-label="Selec. encuesta"> 
        			<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
				</button>
        	</li>
    	</ul>
    	
    	<div id="modalSelectEncuEFQM" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    			     <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&iquest;A qu&eacute; encuesta EFQM deseas dar seguimiento?</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <?php echo $radiosEncus;?>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect " data-dismiss="modal">Cerrar</button>
                            <button id="btnMSEEFQM" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="verEncuestaSeguimiento();">Aceptar</button>
                        </div>
                    </div>
    			</div>
    		</div>
    	</div>

    	<div id="modalEstuChecks" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-lg">
    			<div class="modal-content">
    			     <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Encuestados</h2>
    					</div>
    					<div class="mdl-card__supporting-text p-0">
    					    <div id="contTbEstu" class="form floating-label table_distance">
            			    </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Aceptar</button>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"style="top:5px" onclick="refreshEstudiantes();">
                                <i class="mdi mdi-refresh"></i>
                            </button>
                        </div>
                    </div>
    			</div>
    		</div>
    	</div>
    	
        <div id="modalPersonalEncu" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-lg">
    			<div class="modal-content">
    			     <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Personal encuestado</h2>
    					</div>
    					<div class="mdl-card__supporting-text p-0">
    					    <div id="contTbPers" class="form floating-label table_distance">
            			    </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Aceptar</button>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"style="top:5px" onclick="refreshPersonal();">
                                <i class="mdi mdi-refresh"></i>
                            </button>
                        </div>
                    </div>
    			</div>
    		</div>
    	</div>
        
    	<div class="offcanvas"></div>
    	<form action="C_avance_efqm/imprimirEncuestaBySede" name="formPdfEncu" id="formPdfEncu" method="post"></form>
    	<form action="C_avance_efqm/imprimirListadoTutoresChecklist" name="formPdfEncuSede" id="formPdfEncuSede" method="post"></form>
        <form action="C_avance_efqm/imprimirListadoPersAdmDocente" name="formPdfEncuPers" id="formPdfEncuPers" method="post"></form>
        
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_PUBLIC_SENC?>js/jsAvanceEfqm.js"></script>
    	<script type="text/javascript">
            $('.fixed-table-toolbar').addClass('mdl-card__menu');
            initButtonLoad('btnMSEEFQM');
    	</script>    	
    </body>
</html>