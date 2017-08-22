<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>      
        <title><?php echo NAME_MODULO_MATRICULA;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_MATRICULA?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_MATRICULA?>" />
        
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
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_MATRICULA?>css/submenu.css">
        
        <style type="text/css">
            @media ( max-width : 750px ) {
            	header span.mdl-layout-title a{
            		display: block !important;
            	}            	
        	}            
        </style>
        
	</head>

	<body>
    	
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
            
    		<?php echo $menu ?>
    		          
            <main class='mdl-layout__content is-visible' onscroll="onScrollEvent(this)">  
                <section >
                    <div class="mdl-content-cards">
                        <ol class="breadcrumb breadcumb-filter" id="aulasTitulo" style="display: none;">
                            <li class="active" ><a href="javascript:void(0)">Aulas</a></li>
            				<li class="mdl-button--see__more"><a href="javascript:void(0)" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnVerMasAulas" onclick="verMas(this, 1)">VER M&Aacute;S</a></li>                				
            			</ol>
            			
            			<div id="cont_busqueda1"></div>
           			
            			<ol class="breadcrumb breadcumb-filter m-t-10" id="alumnosTitulo" style="display: none;">
            				<li class="active" ><a href="javascript:void(0)">Estudiantes</a></li>
            				<li class="mdl-button--see__more"><a href="javascript:void(0)" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnVerMasAlumnos" onclick="verMas(this, 2)">VER M&Aacute;S</a></li>
            			</ol>
				
            			<div id="cont_busqueda"></div>
            			
            			<div class="mdl-spinner__position" id="loading_cards" style="display:none">
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
                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored-text" onclick="reintentarBusqueda();">Reintentar</button>
                        </div>
                                                    
                    </div>
                </section>  
            </main>
        </div>
    	
	    <div class="offcanvas"></div>
	    
    	<form action="c_main/logout" name="logout" id="logout" method="post"></form>
        
        <div class="modal fade backModal" id="modalAlumnos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content b-o">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Alumnos</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-0 br-b">
                        
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <div id="cont_tabla_AlumnosAula"></div>
                                </div>
                            </div>
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
        
        <div class="modal small fade" id="modalCompromisosEstudiante" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Compromisos de pago</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-0 p-l-0">
					       <div class="row m-0 p-0">
    					       <div class="col-sm-12 p-0" id="calendarCompromisos">
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
        
        <div class="modal fade backModal" id="modalConfirmDesabilitarAlumno" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="titleDesabilitarAlumno"></h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <small id="msjDesactAlumno"></small>
    					   <br/>
    					   <br/>
    					   <div style="text-align: center;display: none" id="cont_check_retiro">
    					       <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="retirado">
                                  <input type="checkbox" id="retirado" class="mdl-checkbox__input">
                                  <span class="mdl-checkbox__label">¿Es permanente?</span>
                                </label>
    					   </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="botonAE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="cambiarEstadoAlumno();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade backModal" id="modalSolicitudDeTraslado" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card " >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Solicitud de traslado</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
                            <div class="row">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="cmbTipTraslado" name="cmbTipTraslado" class="form-control selectButton" data-live-search="true" onchange="changeTipoTraslado()" >
                			                <option value="">Selecc. Tipo de traslado</option>
                			                <?php echo $comboTipoTraslado;?>
                			            </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="comboSedeTraslado" style="display: none;">
                                    <div class="mdl-select">
                                        <select id="selectSedeDestino" name="selectSedeDestino" class="form-control selectButton" data-live-search="true" onchange="getAulasBySedeTraslado()">
    						                <option value="">Selec. Sede</option>
    					                </select>
					                </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="contMotivoTraslado" style="display: none;">
                                     <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="motivoTraslado" name="motivoTraslado" maxlength="205" rows="5" cols="50"></textarea>        
                                        <label    class="mdl-textfield__label" for="motivoTraslado">Motivo de Traslado</label> 
										<span     class="mdl-textfield__limit" for="motivoTraslado" data-limit="200"></span>  
                                     </div>
                                </div>
                            </div>            
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonST" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="enviarSolicitud();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
    	<div class="modal fade backModal" id="modalDeclaracionJurada"
    		tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel"
    		aria-hidden="true">
    		<div class="modal-dialog modal-sm">
    			<div class="modal-content">
    				<div class="mdl-card">
    					<div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="titleDeclaracionJurada"></h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    						<small id="msjDecJurada"></small> <br /> <br />
    						<div class="col-sm-12 mdl-input-group mdl-input-group__only">
    							<div class="mdl-select">
    								<select id="sedeIngreso" name="sedeIngreso" class="form-control selectButton" data-live-search="true" onchange="">
    									<option value="">Selec.Sede(*)</option>
        					             </select>
    							</div>
    						</div><br /> <br />
    						<small id="msjConfirmaRatificar"></small><br /> <br />
    					</div>
    					<div class="mdl-card__actions">
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect"
    							data-dismiss="modal">Cancelar</button>
    						<button id="botonAE"
    							class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept"
    							onclick="confirmarDeclaracion()">Aceptar</button>
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
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_PUBLIC_MATRICULA?>js/jsmain.js"></script>
    	
	    <script>
     	   imageMainHeader("icon_matricula");
     	   magicIcon();
        </script>
	</body>
</html>