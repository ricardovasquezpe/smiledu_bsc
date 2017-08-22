<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title>Alumno EAI | <?php echo NAME_SMILEDU;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SIST_AVA?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo FAVICON_SIST_AV;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/> 
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>xeditable/bootstrap-editable.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        
	</head>
    <body>
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
    		<?php echo $menu ?>
            <main class='mdl-layout__content is-visible'>    
                <section>
                    <div class="mdl-content-cards">
                        <div class="img-search" id="cont_search_empty">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debes buscar</p>
                            <p>para poder ver lo que buscas.</p>
                        </div>
                        <ol class="breadcrumb" style="display: none;">
                            <li class="active"><strong>Filtro: </strong></li>
            				<li class="null-content"></li>
            				<li class=""></li>
            				<li class=""></li>
            				<li class=""></li>
            			</ol>
                        <div class="mdl-card" style="display: none">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text" id="titleTb">Alumnos EAI - Medidas Rash</h2>
                            </div>
                        	
                            <div class="mdl-card__supporting-text p-0 br-b">
                                <div id="contTablaAlumnosAula" class="table_distance">
                                    <?php echo $tablaAlumnosEai;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
    	    <li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
                <button class="mfb-component__button--main" onclick="abrirCerrarModal('modalFiltro');" data-mfb-label="Filtrar">
                    <i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
                </button>
            </li>
        </ul>
        
       <div class="modal fade backModal" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Filtrar</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div class="row">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="selectSede" name="selectSede" data-live-search="true" title="Sede" class="form-control pickerButn" onchange="getNivelesBySede();">
                                            <option value="">Selec. Sede</option>
                                            <?php echo $optSede; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="selectNivel" name="selectNivel" data-live-search="true" title="Grado" class="form-control pickerButn" onchange="getGradosByNivel();">
                                            <option value="">Selec. Nivel</option>
    	                                </select>
                                   </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="selectGrado" name="selectGrados" data-live-search="true" title="Grado" class="form-control pickerButn" onchange="getAulasByNivelSede();">
                                            <option value="">Selec. Grado</option>
    	                                </select>
	                                </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="selectAula" name="selectAulas" data-live-search="true" title="Aula" class="form-control pickerButn">
                                            <option value="">Selec. Aula</option>
    	                                </select>
	                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMF" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="getAlumnosByAula()">Aceptar</button>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
        
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js"></script>  
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
		<script src="<?php echo RUTA_PLUGINS?>xeditable/bootstrap-editable.min.js"></script> 
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_JS?>jsmantenimiento/jsalumno_eai.js"></script>
   		
		<script type="text/javascript">        	
		initAlumno_Eai();
	    initXEditable();
		$(function() {
            $( "#sortable" ).sortable();
            $( "#sortable" ).disableSelection();
        });
    		(function($) {
            $.fn.clickToggle = function(func1, func2) {
                var funcs = [func1, func2];
                this.data('toggleclicked', 0);
                this.click(function() {
                    var data = $(this).data();
                    var tc = data.toggleclicked;
                    $.proxy(funcs[tc], this)();
                    data.toggleclicked = (tc + 1) % 2;
                });
                return this;
            };
        }(jQuery));
    
    	initSearchTableNew();
        
        var lastScrollTop = 0;
        $(window).scroll(function(event){
           var st = $(this).scrollTop();
           if (st > lastScrollTop){//OCULTAR
        	   $("#menu").fadeOut();
           } else {
        	   if(st + $(window).height() < $(document).height()) {//MOSTRAR
        		   $("#menu").fadeIn();
        	       
    	    	}
           }
           lastScrollTop = st;
        });
		</script>
	</body>
</html>