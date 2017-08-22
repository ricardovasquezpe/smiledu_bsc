<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>        
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>          
		<title>Contactos | <?php echo NAME_MODULO_ADMISION?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_ADMISION?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_ADMISION?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION?>css/contacto.css">
        
        <style>
            #modal .modal-sm{
	           width: 400px;
            }
            
            #modal .modal-sm .modal-content{
	           max-width: 400px;
            }
            
        </style>
	</head>

	<body onload="screenLoader(timeInit);">
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    	    		
    		<?php echo $menu ?>
    		
            <main class='mdl-layout__content' onscroll="onScrollEvent(this)">
                <section class="mdl-layout__tab-panel is-active p-0" id="tab-1">                    
                    <div class="mdl-filter">
                        <div class="p-r-15 p-l-15">
                            <div class="mdl-content-cards mdl-content__overflow">
                                <ul class="nav nav-pills">
                                    <li class="active liprospectos" id="liPros"><a href="#tab-contactados" data-toggle="tab" onclick = "getFamiliasByEstado('<?php echo $contactado?>', this,null)">Contactado</a></li>
                                    <li class="liprospectos"><a href="#tab-por-contactar" data-toggle="tab" onclick = "getFamiliasByEstado('<?php echo $porContactar?>', this,null)">Por contactar</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="mdl-content-cards" style="display:block">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-contactados">
                                <div id="cont_cards_familia1"><?php echo $cardsFamilia?></div>
                                <div class="col-lg-12 m-0" id="cont_btn_ver_mas" style="display: block">
                                    <?php echo $btnVerMas ?>
                                </div>
                                 <div class="mdl-spinner__position" id="loading_cards" style="display: none;">
                                    <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect">
                                        <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active"></div>
                                    </button>   
                                </div> 
                                <div class="img-search" id="cont_search_empty1" style="display:none">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>No se encontraon</p>
                                    <p>resultados.</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-por-contactar">
                                <div id="cont_cards_familia2"></div>
                                <div class="img-search" id="cont_search_empty2" style="display:none">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>No se encontraon</p>
                                    <p>resultados.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel" id="tab-3">
                    <div class="mdl-content-cards">
                        <div id="cont_cards_familia3" class="text-left"></div>
                        <div class="img-search" id="cont_search_empty3" style="display:none">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
                            <p><strong>&#161;Ups!</strong></p>
                            <p>No se encontraron</p>
                            <p>resultados.</p>
                        </div>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel p-0" id="tab-4">
                    <div class="mdl-filter" id="tabsEvaluacion">
                        <div class="p-r-15 p-l-15">
                            <div class="mdl-content-cards mdl-content__overflow">
                                <ul class="nav nav-pills">
                                    <li class="active licontactos" id="li1"><a href="#tab-proceso" data-toggle="tab" onclick = "getFamiliasByEstado('<?php echo $evaluadoProceso?>', this,null)">En proceso</a></li>
                                    <li class="licontactos"><a href="#tab-apto" data-toggle="tab" onclick = "getFamiliasByEstado('<?php echo $evaluadoApto?>', this,null)">Apto</a></li>
                                    <li class="licontactos"><a href="#tab-no-apto" data-toggle="tab" onclick = "getFamiliasByEstado('<?php echo $evaluadoNoApto?>', this,null)">No apto</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="mdl-content-cards" style="display:block">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-proceso">
                                <div id="cont_cards_familia4" class="text-left" ></div>
                                <div class="img-search" id="cont_search_empty4" style="display:none">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>No se encontraron</p>
                                    <p>resultados.</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-apto">
                                <div id="cont_cards_familia5" class="text-left"></div>
                                <div class="img-search" id="cont_search_empty5" style="display:none">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>No se encontraron</p>
                                    <p>resultados.</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-no-apto">
                                <div id="cont_cards_familia6" class="text-left"></div>
                                <div class="img-search" id="cont_search_empty6" style="display:none">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>No se encontraron</p>
                                    <p>resultados.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel p-0" id="tab-7">
                    <div class="mdl-filter" id="tabsPorMatricular">
                        <div class="p-r-15 p-l-15">
                            <div class="mdl-content-cards mdl-content__overflow">
                                <ul class="nav nav-pills">
                                     <li class="active liPorMatricular" id="liPago"><a href="#tab-cuota" data-toggle="tab" onclick = "getFamiliasByEstado('<?php echo $pagoCuota?>', this,null)">Pag&oacute; cuota de ingreso</a></li>
                                     <li class="liPorMatricular"><a href="#tab-matricula" data-toggle="tab" onclick = "getFamiliasByEstado('<?php echo $pagoMatricula?>', this,null)">Pag&oacute; matr&iacute;cula</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                     
                    <div class="mdl-content-cards" style="display:block">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-cuota">
                                <div id="cont_cards_familia7" class="text-center"></div>
                                <div class="img-search" id="cont_search_empty7" style="display:none">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>No se encontraron</p>
                                    <p>resultados.</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-matricula">
                                <div id="cont_cards_familia8" class="text-center"></div>
                                <div class="img-search" id="cont_search_empty8" style="display:none">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>No se encontraron</p>
                                    <p>resultados.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel" id="tab-9">
                    <div class="mdl-content-cards">
                        <div id="cont_cards_familia9" class="text-left"></div>
                        <div class="img-search" id="cont_search_empty9" style="display:none">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
                            <p><strong>&#161;Ups!</strong></p>
                            <p>No se encontraron</p>
                            <p>resultados.</p>
                        </div>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel p-0" id="tab-10">
                    <div class="mdl-filter" id="tabsVerano">
                        <div class="p-r-15 p-l-15">
                            <div class="mdl-content-cards mdl-content__overflow">
                                <ul class="nav nav-pills">
                                     <li class="active lisummer" id="lisport"><a href="#tab-sport" data-toggle="tab" onclick = "getFamiliasByEstado('<?php echo $verano?>', this,'<?php echo $sport?>')">SPORT SUMMER</a></li>
                                     <li class="lisummer"><a href="#tab-creative" data-toggle="tab" onclick = "getFamiliasByEstado('<?php echo $verano?>', this,'<?php echo $creative?>')">CREATIVE SUMMER</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="mdl-content-cards" style="display:block">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-sport">
                            	<div id="cont_cards_familia10" class="text-left"></div>
		                        <div class="img-search" id="cont_search_empty10" style="display:none">
		                            <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
		                            <p><strong>&#161;Ups!</strong></p>
		                            <p>No se encontraron</p>
		                            <p>resultados.</p>
		                        </div>
                            </div>
                            <div class="tab-pane active" id="tab-creative">
                            	<div id="cont_cards_familia11" class="text-left"></div>
		                        <div class="img-search" id="cont_search_empty11" style="display:none">
		                            <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
		                            <p><strong>&#161;Ups!</strong></p>
		                            <p>No se encontraron</p>
		                            <p>resultados.</p>
		                        </div>
                            </div>
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
                 <button class="mfb-component__button--main" data-toggle="modal"  data-mfb-label="Registrar" onclick="llenarFormulario()">
                     <i class="mfb-component__main-icon--active mdi mdi-edit"></i>
                 </button>
                <ul class="mfb-component__list">
                    <?php echo (isset($fabFiltrar) ? $fabFiltrar : null)?>                     
                </ul>    
            </li>
        </ul>
        
        <!-- Modals -->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-l-0 p-r-0">
					       <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect"> 
    					      <div class="mdl-tabs__tab-bar">
                                  <a href="#filtroGrados" class="mdl-tabs__tab is-active">Grado</a>
                                  <a href="#filtroCanalCom" class="mdl-tabs__tab">Canal de comunicación</a>
                              </div>
                               <div class="mdl-tabs__panel is-active" id="filtroGrados">			
        					       <div class="row-fluid p-t-15">
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
        					               <div class="mdl-select">
            					               <select id="selectYear" name="selectYear" class="form-control selectButton" data-live-search="true"  
            					                data-noneSelectedText="Selec. A&ntilde;o" onchange="getFamiliasByYear('selectYear','selectSede','selectGradoNivel')">
                        			                <option value="">Selec. A&ntilde;o</option>
                        			                <?php echo $comboYear?>
                        			           </select>
                    			           </div>
        					           </div>
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
        					               <div class="mdl-select">
            					               <select id="selectSede" name="selectSede" class="form-control selectButton"data-live-search="true" 
            					               data-noneSelectedText="Selec. Sede" onchange="getFamiliasBySedeInteres('selectYear','selectSede','selectGradoNivel')">
                        			                <option value="">Selec. Sede</option>
                        			           </select>
                    			           </div>
        					           </div>
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
        					               <div class="mdl-select">
            					               <select id="selectGradoNivel" name="selectGradoNivel" class="form-control selectButton"data-live-search="true" 
                                                data-noneSelectedText="Selec. Nivel y Grado" onchange="getFamiliasByGradoNivel('selectYear','selectSede','selectGradoNivel')">
                        			                <option value="">Selec. Nivel y Grado</option>
                        			           </select>
                    			           </div>
        					           </div>
        					       </div>
    					       </div>
    					       <div class="mdl-tabs__panel" id="filtroCanalCom">			
        					       <div class="row-fluid p-t-15">
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
        					               <div class="mdl-select">
            					               <select id="selectCanalComFiltro" name="selectCanalComFiltro" class="form-control selectButton" data-live-search="true"
            					                data-noneSelectedText="Selec. Canal de comunicación" onchange="getFamiliasByCanalComunicacion()">
                        			                <option value="">Selec. Canal de comunicación</option>
                        			                <?php echo $comboCanales?>
                        			           </select>
                    			           </div>
        					           </div>
        					       </div>
        					       <div class="col-sm-12 mdl-input-group p-l-10 p-r-15">
    				                    <div class="mdl-icon mdl-icon__button">
    				                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconFecInicio">
    				                            <i class="mdi mdi-event_note"></i>
				                            </button>
			                            </div>        					           
        				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="fechaInicioFiltro" name="fechaInicioFiltro" maxlength="10" onchange="getFamiliasByCanalComunicacion()">        
                                            <label class="mdl-textfield__label" for="fechaInicioFiltro">Fecha Inicio</label>                            
                                        </div>
    				               </div>
    				               <div class="col-sm-12 mdl-input-group p-l-10 p-r-15">
    				                    <div class="mdl-icon mdl-icon__button">
    				                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconFecFin">
    				                            <i class="mdi mdi-event_note"></i>
				                            </button>
			                            </div>        				               
        				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="fechaFinFiltro" name="fechaFinFiltro" maxlength="10" onchange="getFamiliasByCanalComunicacion()">        
                                            <label class="mdl-textfield__label" for="fechaFinFiltro">Fecha Fin</label>                            
                                        </div>
    				               </div>
    					       </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions p-t-20">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" id="botonFiltro" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalLlamadas" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card" >
                        <div class="mdl-card__title p-b-0">
    						<h2 class="mdl-card__title-text">Seguimiento</h2>
    					</div>
    					<div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>
    					</div>
					    <div class="mdl-card__supporting-text p-0 br-b">
					       <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
    					       <div class="mdl-tabs__tab-bar">
    					           <a href="#llamadas" class="mdl-tabs__tab tabLlamadas is-active" id="tabLlamadas">Agregar seguimiento</a>
                                   <a href="#historial" class="mdl-tabs__tab tabLlamadas" >Historial</a>
                               </div>
    					       <div class="mdl-tabs__panel panelLlamadas is-active" id="llamadas">
        					       <div class="row-fluid">
        					           <div class="col-md-12 col-lg-12">
        					               <div class="col-md-6 col-lg-6" style="font-size:15px">
                					           <div class="col-sm-12 col-lg-12 p-t-10 p-b-20">       
                					               <div class="mdl-card__supporting-text br-b">
                					                    <strong style="font-size:17px;">
                    					                    <div class="col-md-6">                                            					               
                    					                       <span></i>Celular:</span>
                						                    </div>
                						                    <div class="col-md-6">
                						                       <span name="numTelefono" id="numTelefono">Tel&eacute;fono</span>
                						                    </div>
            						                    </strong>  
            						               </div>
                    					       </div>
                					           <div class="col-sm-12 col-lg-12  p-b-20">
                					               <div class="mdl-card__supporting-text br-b" >
                					                    <strong style="font-size:17px;">
                    					                    <div class="col-md-6">
                        					                   <span>Correo:</span>
                						                    </div>
                						                    <div class="col-md-6">
                						                       <span name="correoSeguimiento" id="correoSeguimiento">Correo</span> 
                						                    </div>
            						                    </strong>
            						               </div>
                    					       </div>
                					       </div>
                					       <div class="col-md-6 col-lg-6" >
                    					       <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                                    <div class="mdl-select">                    					           
                        					            <select id="selectTipoLlamada" name="selectTipoLlamada" class="form-control selectButton"
                                                           data-live-search="true"  data-noneSelectedText="Selec. Tipo de seguimiento">
                    						               <option value="">Selec. Tipo de seguimiento</option>
                    						               <?php echo $comboSeguimiento?>
                    						            </select>
                						            </div>
                    					       </div>  
                    					       <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                    					           <div class="mdl-select">
                        					           <select id="selectEvento" name="selectEvento" class="form-control selectButton"
                                                            data-live-search="true" data-noneSelectedText="Selec. Evento">
                    						                <option value="">Selec. Evento</option>
                    						           </select>
                						           </div>
                    					       </div>
                    				       </div>              				              
                					       <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                					           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="observacion" name="observacion" maxlength="110" max>        
                                                   <label class="mdl-textfield__label">Observaciones</label>
                                                   <span class="mdl-textfield__limit" for="observacion" data-limit="100"></span> 
                                               </div>
                					       </div>
                                      </div>  
                        			   <div class="mdl-card__actions">
                                           <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                           <button id="botonS" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="agregarLlamada(1)">Guardar</button>
                                       </div>   
                                   </div>
    					       </div>
    					       <div class="mdl-tabs__panel panelLlamadas" id="historial">			
        					       <div class="row p-0 m-0  p-t-15">
    					           <div id="cont_table_llamadas"></div>
    					           </div>
                					<div class="mdl-card__actions p-t-20">
                                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Cerrar</button>
                                    </div>
    					       </div>
					       </div>
    					</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalInvitarContacto" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card" >
                        <div class="mdl-card__title p-b-0">
    						<h2 class="mdl-card__title-text">Invitar a contacto</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-rl-0">
					        <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                <div class="mdl-select">
                                    <select id="selectEventoInvitar" name="selectEventoInvitar" class="form-control selectButton" data-live-search="true" 
    			                     data-noneSelectedText="Selec. Evento" onchange="selectByTipoEvento('selectEventoInvitar')">
                                        <option value="">Selec. Evento</option>
        			                </select>
    			                </div>
				            </div>
					        <div id="cont_tb_familiares_invitar"></div>
    					</div>
    					<div class="mdl-card__actions p-t-15">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="invitarContacto()" data-dismiss="modal" id="btnInvitar" disabled>Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalGuardarLlamada" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card" >
                        <div class="mdl-card__title p-b-0">
    						<h2 class="mdl-card__title-text">&#191;Desea registrar el tipo de seguimiento?</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-20">
					       <div class="mdl-select">
                               <select id="selectTipoLlamada2" name="selectTipoLlamada2" class="form-control selectButton m-t-10 m-b-10"
                                   data-live-search="true"  data-noneSelectedText="Selec. Tipo de seguimiento">
					               <option value="">Selec. Tipo de seguimiento</option>
					               <?php echo $comboSeguimiento?>
					           </select>
                           </div>
					        <div id="cont_tb_familiares_invitar"></div>
    					</div> 
        				<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonGL" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" type="button" onclick="guardarLlamada(2)">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalAgregarPostulante" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Agregar postulante</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
			               <div class="row p-0 m-0">
			                   <div class="col-sm-6 col-md-6 mdl-input-group">
			                        <div class="mdl-icon"><i class="mdi mdi-account_circle"></i></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="apellidoPatPostulanteCrear" name="apellidoPatPostulanteCrear" maxlength="100">        
                                        <label class="mdl-textfield__label" for="apellidoPatPostulanteCrear">Apellido Paterno Postulante (*)</label>                            
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-6 mdl-input-group">
				                    <div class="mdl-icon"></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="apellidoMatPostulanteCrear" name="apellidoMatPostulanteCrear" maxlength="100">        
                                        <label class="mdl-textfield__label" for="apellidoMatPostulanteCrear">Apellido Materno Postulante (*)</label>                            
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-6 mdl-input-group">
				                    <div class="mdl-icon"></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="nombrePostulanteCrear" name="nombrePostulanteCrear" maxlength="100">        
                                        <label class="mdl-textfield__label" for="nombrePostulanteCrear">Nombre Postulante (*)</label>                            
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-6 mdl-input-group">
				                    <div class="mdl-icon"><i class="mdi mdi-event_note"></i></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="fecNaciPostulanteCrear" name="fecNaciPostulanteCrear" data-inputmask="'alias': 'date'" maxlength="10">        
                                        <label class="mdl-textfield__label" for="fecNaciPostulanteCrear">Fecha Nacimiento</label>                            
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-6 mdl-input-group">
				                   <div class="mdl-icon"><i class="mdi mdi-wc"></i></div>
				                   <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectSexoPostulanteCrear" name="selectSexoPostulanteCrear" data-live-search="true">
                			                <option value="">Selec. Sexo</option>
                			                <?php echo (isset($comboSexo) ? $comboSexo : null)?>
                			           </select>
            			           </div>
					           </div>
				               <div class="col-sm-6 col-md-6 mdl-input-group">
				                   <div class="mdl-icon"><i class="mdi mdi-chrome_reader_mode"></i></div>
				                   <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectTipoDocPostulanteCrear" name="selectTipoDocPostulanteCrear" data-live-search="true"onchange="habilitarCampo('selectTipoDocPostulanteCrear','numeroDocPostulanteCrear'); changeMaxlength('selectTipoDocPostulanteCrear','numeroDocPostulanteCrear')">
                			                <option value="">Selec. Tipo Documento</option>
                			                <?php echo (isset($comboTipoDocumento) ? $comboTipoDocumento : null)?>
                			           </select>
            			           </div>
					           </div>
					           <div class="col-sm-6 col-md-6 mdl-input-group">
					                <div class="mdl-icon"></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="numeroDocPostulanteCrear" name="numeroDocPostulanteCrear" maxlength="8">        
                                        <label class="mdl-textfield__label" for="numeroDocPostulanteCrear">N&uacute;mero de documento</label>
                                    </div>
				               </div>
					           <div class="col-sm-6 col-md-6 mdl-input-group">
					               <div class="mdl-icon"><i class="mdi mdi-schoowl"></i></div>
					               <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectColegioProcPostulanteCrear" name="selectColegioProcPostulanteCrear" data-live-search="true">
                			                <option value="">Selec. Colegio de procedencia</option>
                			                <?php echo (isset($comboColegios) ? $comboColegios : null)?>
                			           </select>
            			           </div>
					           </div>
				               <div class="col-sm-6 col-md-6 mdl-input-group">
				                   <div class="mdl-icon"></div>
					               <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectGradoNivelPostulanteCrear" name="selectGradoNivelPostulanteCrear" data-live-search="true" 
    					                       onchange="getSedesByNivel('selectGradoNivelPostulanteCrear', 'selectSedePostulanteCrear')">
                			                <option value="">Selec. Grado y Nivel</option>
                			                <?php echo (isset($comboGradoNivel) ? $comboGradoNivel : null)?>
                			           </select>
            			           </div>
					           </div>
					           <div class="col-sm-6 col-md-6 mdl-input-group">
					               <div class="mdl-icon"></div>
					               <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectSedePostulanteCrear" name="selectSedePostulanteCrear" data-live-search="true" >
                			                <option value="">Selec. Sede de inter&eacute;s</option>
                			           </select> 
            			           </div>
					           </div>
			               </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonAHF" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="agregarHermanoFamilia()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalEnviarMensajePariente" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Enviar Mensaje</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-t-0">
			               <div class="row p-0 m-0">
			                   <div class="col-sm-12 p-0">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="asuntoCorreoEnviar" name="asuntoCorreoEnviar" maxlength="100">        
                                        <label class="mdl-textfield__label" for="asuntoCorreoEnviar">Asunto</label>                            
                                    </div>
				               </div>
				               <div class="col-sm-12 p-0">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="mensajeCorreoEnviar" name="mensajeCorreoEnviar" maxlength="200"></textarea>        
                                        <label class="mdl-textfield__label" for="mensajeCorreoEnviar">Mensaje</label>                            
                                    </div>
				               </div>
			               </div>
    					</div>
    					<div class="mdl-card__actions p-t-0">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="enviarCorreoPariente()">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalConfirmDeleteFam" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Eliminar Familia</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-t-0">
			               <div class="row p-0 m-0">
			                   <div class="col-sm-12 p-0">
    				                ¿Seguro de eliminar a la familia seleccionada?
				               </div>
			               </div>
    					</div>
    					<div class="mdl-card__actions p-t-20">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonEF" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised delete" onclick="elimiarFamilia()">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalConfirmarDatosPostulantes" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Falta llenar datos</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-t-0">
			               <div class="row p-0 m-0">
			                   <div class="col-sm-12 col-md-12 mdl-input-group mdl-input-group__only">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="apellidoPatPostulanteConfirmar" name="apellidoPatPostulanteConfirmar" maxlength="100">        
                                        <label class="mdl-textfield__label" for="apellidoPatPostulanteConfirmar">Apellido Paterno Postulante (*)</label>                            
                                    </div>
				               </div>
				               <div class="col-sm-12 col-md-12 mdl-input-group mdl-input-group__only">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="apellidoMatPostulanteConfirmar" name="apellidoMatPostulanteConfirmar" maxlength="100">        
                                        <label class="mdl-textfield__label" for="apellidoMatPostulanteConfirmar">Apellido Materno Postulante (*)</label>                            
                                    </div>
				               </div>
				               <div class="col-sm-12 col-md-12 mdl-input-group mdl-input-group__only">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="nombresPostulanteConfirmar" name="nombresPostulanteConfirmar" maxlength="100">        
                                        <label class="mdl-textfield__label" for="nombresPostulanteConfirmar">Nombres Postulante (*)</label>                            
                                    </div>
				               </div>
				               <div class="col-sm-12 col-md-12 mdl-input-group">
					               <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectGradoNivelPostulanteConfirmar" name="selectGradoNivelPostulanteConfirmar" data-live-search="true" >
                			                <option value="">Selec. Grado y Nivel</option>
                			                <?php echo (isset($comboGradoNivel) ? $comboGradoNivel : null)?>
                			           </select>
            			           </div>
					           </div>
				           </div>
    					</div>
    					<div class="mdl-card__actions p-t-20">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonEF" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="guardarDatosContacto()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalInscripcionVerano" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Inscripci&oacute;n verano</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-rl-0">
						    <div class="m-t-10 m-b-10 p-r-10 p-l-10">
<!-- 						    	<small class="m-t-10" id ="msjVerano">&#191;Est&aacute; seguro que desea  -->
<!-- 	                            ir a la secci&oacute;n de pagos para continuar con la inscripci&oacute;n?</small> -->
						    	<small class="m-t-10" id ="msjVerano">Al confirmar el alumno estar&aacute; habilitado en 
						    	el m&oacute;dulo de pagos para inscribirse a Verano</small>
	    					</div>
						    <div class="m-t-10 m-b-10 p-r-10 p-l-10">
						    	<small class="m-t-10" id ="msjVeranoYear">Debes seleccionar el a&ntilde;o de inter&eacute;s del postulante</small>
	    					</div>
					    	<div class="col-sm-12 mdl-input-group mdl-input-group__only">
        						<div class="mdl-select">
            						<select id="selectYearVerano" name="selectYearVerano" class="form-control selectButton" data-live-search="true"  
            					     data-noneSelectedText="Selec. A&ntilde;o" onchange="habilitarConfirmarcionVerano('btnInscribir')">
                        				<option value="">Selec. A&ntilde;o</option>
                        			    	<?php echo $comboYearVerano?>
                        			</select>
                    			</div>
        					</div>
    					<div class="mdl-card__actions p-t-15">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="goToInscripcionVerano()" data-dismiss="modal" id="btnInscribir" disabled>Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    	
    	<script src="<?php echo RUTA_JS;?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>jquery-mask/jquery.mask.min.js"></script>
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>
        <script src="<?php echo RUTA_PUBLIC_ADMISION?>js/jscontactos.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mark/jquery.highlight.js"></script>
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        
        <script>
            init();
            var cantidadScroll = <?php echo $cantidadScroll?>;
            var max_grupo      = <?php echo $max_cod_grupo?>;
            initButtonLoad('btnInvitar');
        </script>
	</body>
</html>