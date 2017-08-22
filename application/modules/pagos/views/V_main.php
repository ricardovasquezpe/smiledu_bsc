<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>    
        <title><?php echo NAME_MODULO_PAGOS;?></title>
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
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>highcharts/">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">   
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/submenu.css">
        
        <style type="text/css">
            @media ( max-width : 768px ) {
            	header span.mdl-layout-title a{
            		display: block !important;
            	}
        	}            
        </style>        
	</head>

	<body>
	
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		
    		<?php echo $menu ?>
    		
            <main class='mdl-layout__content is-visible' onscroll="onScrollEvent(this)">
                <section>
                    <div class="mdl-content-cards">
                        <div class="m-b-10" id="filtroMain" style="display:none;">
	                       <ol class="breadcrumb">
                				<li class="active">Filtro:</li>
                				<li id="laelYear" style="display:none"></li>
                				<li id="laelSede" style="display:none"></li>
                				<li id="laelNivel" style="display:none"></li>
                				<li id="laelGrado" style="display:none"></li>
                				<li id="laelAula" style="display:none"></li>
            				</ol>
                        </div>
                    </div>
                    
                    <div class="mdl-content-cards">
                        <ol class="breadcrumb" id="aulasTitulo" style="display: none;">               
                            <li class="active"><a href="javascript:void(0)">Aulas</a></li>                				
            			</ol>
            			<div id="contAulas"></div>
            			<ol class="breadcrumb" id="alumnosTitulo" style="display: none;">
            				<li class="active"><a href="javascript:void(0)">Estudiantes</a></li>
            			</ol>
            			<div id="contAlumnos"></div> 
            			<div class="mdl-spinner__position" id="loading_cards" style="display: none;">
                            <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect">
                                <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active"></div>
                            </button>   
                        </div>               			
                              
                        <div class="img-search" id="cont_imagen_magic">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/magic_empty_state.png">
                            <p><strong>Hola!</strong></p>
                            <p>Prueba el buscador</p>
                            <p>m&aacute;gico.</p>
                        </div>
                        <div class="img-search" id="cont_search_empty" style="display: none;">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/magic_not_found.png">
                            <p><strong>&#161;Ups!</strong></p>
                            <p>No se encontraron</p>
                            <p>resultados.</p>
                            <button class="mdl-button mdl-js-button mdl-button--raised" onclick="reintentarBusqueda();">Reintentar</button>
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
        
        <?php  if(_getSesion(PAGOS_ROL_SESS) == ID_ROL_SECRETARIA || _getSesion(PAGOS_ROL_SESS) == ID_ROL_PROMOTOR || _getSesion(PAGOS_ROL_SESS) == ID_ROL_RESP_COBRANZAS) {?>
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover" >
            <li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" id="filtroByYear">
                <button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltroYear" data-mfb-label="Filtrar">
                    <i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
                </button>
            </li>
        </ul>
        <?php }?>
        
        <div class="modal fade" id="modalFiltroYear" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content ">
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtro</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
					       <div class="row-fluid">
				               <div class="col-sm-6 p-0 mdl-input-group mdl-input-group__only">
					               <select id="selectYear" name="selectYear" class="form-group pickerButn" onchange="getSedesByYear();" data-live-search="true" >
					                   <option value="">Selec. Año</option>
					                   <?php echo $optYear;?>
                                   </select>
                               </div>
					           <div class="col-sm-6 p-0 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select id="selectSedeByYear" name="selectSedeByYear" class="form-group pickerButn" onchange="getNivelesBySede();" data-live-search="true" >
    					                   <option value="">Selec. Sede</option>
                                       </select>
                                   </div>
					           </div>
					           <div class="col-sm-6 p-0 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select id="selectNivelByYear" name="selectNivelByYear" class="form-group pickerButn" onchange="getGradosByNivel();" data-live-search="true" >
    					                   <option value="">Selec. nivel</option>
                                       </select>
                                   </div>
					           </div>
					           <div class="col-sm-6 p-0 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select id="selectGradoByYear" name="selectGradoByYear" class="form-group pickerButn" onchange="getAulasByNivelSede();" data-live-search="true" >
    					                   <option value="">Selec. grado</option>
                                       </select>
                                   </div>
					           </div>
					           <div class="col-sm-12 p-0 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select id="selectAulaByYear" name="selectAulaByYear" class="form-group pickerButn" onchange="getAlumnosByAula();" data-live-search="true" >
    					                   <option value="">Selec. aula</option>
                                       </select>
                                   </div>
 					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect " data-dismiss="modal">Cerrar</button>
                            <button id="botonFM" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalCaja" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#161;Aperturar Caja!</h2>
    					</div>
					    <div class="mdl-card__supporting-text" id="formularioRegistro"> 
				    	    <small>Recuerda: No podr&aacute;s realizar ninguna acci&oacute;n mientras no apertures tu caja.</small>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Omitir</button>
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" href="<?php echo base_url()?>pagos/c_caja">IR A CAJA</a>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="detalleAula" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="titleDetaAula"></h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0">
                             <div id="contTableAula"></div>
                        </div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Aceptar</a>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="detalleAlumno" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="titleDetaAlumno"></h2>
    					</div>
					    <div class="mdl-card__supporting-text" id="contGraficoAlumno"> 
				    	    
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">ACEPTAR</a>
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
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highstock.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts-more.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/exporting.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/heatmap.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jsmain.js"></script>            
                
        <script>
            if(<?php echo $notificacionCaja?> == true){
            	abrirCerrarModal('modalCaja');
            }
        	imageMainHeader("icon_pagos");
        	magicIcon();
        	init();
        </script>
        
	</body>
</html>