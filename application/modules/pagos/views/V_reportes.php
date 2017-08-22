 <?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>      
        <title>Reportes | <?php echo NAME_MODULO_PAGOS;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_PAGOS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_PAGOS;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>mdl/css/material.indigo.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">        
        <link type='text/css' rel="stylesheet" href="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/css/jquery.treegrid.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />   
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">            
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/movimiento.css">  
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/submenu.css">
        
        <style type="text/css">
            .ct-line.ct-threshold-above, .ct-point.ct-threshold-above, .ct-bar.ct-threshold-above {
              stroke: #f05b4f;
            }
            
            .ct-line.ct-threshold-below, .ct-point.ct-threshold-below, .ct-bar.ct-threshold-below {
              stroke: #59922b;
            }
            
            .ct-area.ct-threshold-above {
              fill: #f05b4f;
            }
            
            .ct-area.ct-threshold-below {
              fill: #59922b;
            }
            .ct-series-a .ct-bar.ct-threshold-above {
              stroke: #f05b4f;
            }
            
            .ct-series-a .ct-bar.ct-threshold-below {
              stroke: #59922b;
            }
        </style>
	</head>

	<body>
	   
	    <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'> 
    		<?php echo $menu ?>    	
    		<main class='mdl-layout__content is-visible'>    		
                <section class="mdl-layout__tab-panel is-active" id="tab-vencimiento">
                    <div class="mdl-content-cards">
                        <div class="mdl-card" style="display: none" id="tablaV">
                            <div class="mdl-card__title ">
                                <h2 class="mdl-card__title-text">Pensiones Vencidas</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b" > 
                                <div id="tableVencidos" ></div>
                                <div id="container_grafico_vencidos" style="display :block"></div>
                            </div>
                            <div class="mdl-card__menu">
                            	<div class="pull-right form-inline">
							        <button class="mdl-button mdl-js-button mdl-button--icon" onclick="buildChartVencidos('tab2'); changeVisibilityByIconTab2($(this));">
							            <i class="mdi mdi-insert_chart" id="iconVencidos"></i>
							        </button>
							        <button class="mdl-button mdl-js-button mdl-button--icon">
                                        <i class="mdi mdi-more_vert"></i>
                                    </button>
								</div> 
                            </div>
                        </div>
                        <div class="img-search" id="cont_filter_empty1" >
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar las pensiones vencidas.</p>                             
                        </div>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel" id="tab-puntual" style="display: none">
                    <div class="mdl-content-cards">
                        <div class="mdl-card" style="display :none" id="tablaPU">
                            <div class="mdl-card__title ">
                                <h2 class="mdl-card__title-text">Pagos Puntuales</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b" > 
                                <div id="tablePuntuales" ></div>
                                <div id="container_grafico_puntuales" style="display :block"></div>
                            </div>
                            <div class="mdl-card__menu">
                            	<div class="pull-right form-inline">
							        <button class="mdl-button mdl-js-button mdl-button--icon" onclick="buildChartPuntuales('tab3');changeVisibilityByIconTab3($(this));">
							            <i class="mdi mdi-insert_chart" id="iconPuntuales"></i>
							        </button>
							        <button class="mdl-button mdl-js-button mdl-button--icon">
                                        <i class="mdi mdi-more_vert"></i>
                                    </button>
								</div> 
                            </div>
                        </div>
                        <div class="img-search" id="cont_filter_empty2">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar los pagos puntuales.</p>                             
                        </div>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel" id="tab-pagados" style="display: none">
                    <div class="mdl-content-cards">
                        <div class="mdl-card" style="display :none" id="tablaP">
                            <div id="highchartDemo"></div>
                            <div class="mdl-card__title ">
                                <h2 class="mdl-card__title-text">Pensiones Pagadas</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b" > 
                                <div id="tablePagados"></div>
                            	<div id="container_grafico_pagados" style="display :block"></div>
                            </div>
                            <div class="mdl-card__menu">
                            	<div class="pull-right form-inline">
							        <button class="mdl-button mdl-js-button mdl-button--icon" onclick="buildChartByTab('tab1');changeVisibilityByIconTab1($(this));">
							            <i class="mdi mdi-insert_chart" id="iconPagados"></i>
							        </button>
								</div> 
                            </div>
                        </div>   
                        <div class="img-search" id="cont_filter_empty3">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar las pensiones pagadas</p>                             
                        </div>
                        <div id="chartist"></div> 
                    </div>    
                </section>
                
                <section class="mdl-layout__tab-panel" id="tab-verano" style="display: none">
                    <div class="mdl-content-cards">
                        <div class="mdl-card" style="display :none" id="tablaVera">
                            <div id="highchartDemo"></div>
                            <div class="mdl-card__title ">
                                <h2 class="mdl-card__title-text">Pensiones Pagadas</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b" > 
                                <div id="tableVerano">
                                
                                </div>
                            	<div id="container_grafico_verano" style="display :block"></div>
                            </div>
                            <div class="mdl-card__menu">
                            	<div class="pull-right form-inline">
							        <button class="mdl-button mdl-js-button mdl-button--icon" style="display:none;" onclick="buildChartByTab('tab1');changeVisibilityByIconTab1($(this));">
							            <i class="mdi mdi-insert_chart" id="iconPagados"></i>
							        </button>
							        <button class="mdl-button mdl-js-button mdl-button--icon" onclick="downloadPDFByFiltro();">
							            <i class="mdi mdi-picture_as_pdf"></i>
							        </button>
								</div> 
                            </div>
                        </div>   
                        <div class="img-search" id="cont_filter_empty_verano">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar los talleres de verano</p>                             
                        </div>
                        <div id="chartist"></div> 
                    </div>    
                </section>             
    
                <section class="mdl-layout__tab-panel" id="tab-auditoria" style="display: none">
                    <div class="mdl-content-cards">
                        <div class="mdl-card" style="display :none" id="tablaC">
                            <div class="mdl-card__title ">
                                <h2 class="mdl-card__title-text">Exportaciones de Contabilidad</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b"> 
                                <div id="tableContabilidad"></div>
                            </div>
                        </div>
                        <div class="mdl-card" style="display :none" id="tablaSB">
                            <div class="mdl-card__title ">
                                <h2 class="mdl-card__title-text">Importacion y Exportacion</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b"> 
                                <div id="tableSedesBanco"></div>
                            	<div id="container_grafico_bancos" style="display :block"></div>
                            </div>
                            <div class="mdl-card__menu">
                            	<div class="pull-right form-inline">
							        <button class="mdl-button mdl-js-button mdl-button--icon" onclick="buildChartByBanco('tab4');changeVisibilityByIconTab($(this));">
							            <i class="mdi mdi-insert_chart" id="iconBancos"></i>
							        </button>
								</div> 
                            </div>
                        </div>
                        <div class="mdl-card" style="display :none" id="tablaM">
                            <div class="mdl-card__title ">
                                <h2 class="mdl-card__title-text">Movimientos Escolares</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b" > 
                                <div id="tableMovimiento"></div>
                            </div>
                        </div>
                        <div class="mdl-card" style="display :none" id="tablaMB">
                            <div class="mdl-card__title ">
                                <h2 class="mdl-card__title-text">Pagos En El Banco</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b" > 
                                <div id="tablePagosBanco"></div>
                            </div>
                        </div>
                        <div class="img-search" id="cont_filter_empty4">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar la auditoria del sistema</p>                             
                        </div>
                    </div>
                </section>
            </main>	
        </div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
        		<button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltrarVencidos" data-mfb-label="Filtrar pensiones vencidas">
        			<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
        			<i class="mfb-component__main-icon--active mdi mdi-filter_list"></i>
        		</button>
        	</li>
        </ul>	
        
        <div class="modal small fade" id="modalAlumnosDetalles" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="nombreCompleto">Alumnos</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0">
					   	 	<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
					    		<div class="mdl-tabs__tab-bar mdl-js-ripple-effect" id="tabsDetalles"></div>
					            <div class="mdl-tabs__panel is-active detalleDescripcion" id="tabPensiones">
					      			<div class="col-sm-12 p-0" id="tablePensVenc"></div>
					       		</div>
					       		<div id="apoderados"></div>
    						</div>
                    	</div>
                    	<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                	</div>
            	</div>     
        	</div>
        </div>
        <div class="modal small fade" id="modalAlumnosPagos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Estudiantes</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
					       <div class="col-sm-12 p-0" id="tableAlumnos">
					       </div>	   
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal small fade" id="modalEmpresaHistorial" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id = "tittleConta">Historial</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
					       <div class="col-sm-12 p-0" id="tableContaHistorial">
					       </div>	   
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal small fade" id="modalPersonaHistorial" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id = "tittleMov">Historial</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
					       <div class="col-sm-12 p-0" id="tableMovHistorial">
					       </div>	   
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal small fade" id="modalBancoHistorial" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id = "tittleBanco">Historial</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
					       <div class="col-sm-12 p-0" id="tableBancoHistorial">
					       </div>	   
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalFiltrarPagados" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content ">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtro</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-0 p-l-0">    				
					       <div class="row-fluid">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <select id="selectSedePP" name="selectSedePP" class="form-group pickerButn" onchange="getNivelesBySedePP();" data-live-search="true">
                                       <option value="">Selec. Sede</option>
                                       <?php echo $optSede; ?>
                                    </select>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <select id="selectNivelPP" name="selectNivelPP" class="form-group pickerButn" onchange="getGradosByNivelPP();" data-live-search="true">
                                       <option value="">Selec. Nivel</option>
                                    </select>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <select id="selectGradoPP" name="selectGradoPP" class="form-group pickerButn" onchange="getAulasByGradoPP();" data-live-search="true">
                                       <option value="">Selec. Grado</option>
                                    </select>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <select id="selectAulaPP" name="selectAulaPP" class="form-group pickerButn" onchange="getAlumnosByAulaPP();" data-live-search="true">
					                   <option value="">Selec. Aula</option>
                                   </select>
					           </div>
					       	   <div class="col-sm-12 p-r-20 m-l-5 mdl-input-group">
                                    <div class="mdl-icon mdl-icon__button">
                                        <button class="mdl-button mdl-js-button mdl-button--icon">				                            
                                            <i class="mdi mdi-today"></i>
                                        </button>
                                    </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="fecInicioPP" name="fecInicioPP" maxlength="10"/>
                                        <label class="mdl-textfield__label" for="fecInicioPP">Fecha Inicio</label>
                                        <span class="mdl-textfield__error"></span>
                                    </div>                                       
                         	   </div>
                               <div class="col-sm-12 p-r-20 m-l-5 mdl-input-group">
                            		<div class="mdl-icon mdl-icon__button">
                                        <button class="mdl-button mdl-js-button mdl-button--icon">				                            
                                            <i class="mdi mdi-today"></i>
                                        </button>
                                    </div>
                                	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	                                    <input class="mdl-textfield__input" type="text" id="fecFinPP" name="fecFinPP" maxlength="10"/>
	                                    <label class="mdl-textfield__label" for="fecFinPP">Fecha Fin</label>
	                                    <span class="mdl-textfield__error"></span>
	                                </div>
                               </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect " data-dismiss="modal" onclick="getAlumnosByAulaPP();">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        <div class="modal fade" id="modalFiltrarVencidos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content ">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtro</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-0 p-l-0">    				
					       <div class="row-fluid">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <select id="selectSedeV" name="selectSedeV" class="form-group pickerButn" onchange="getNivelesBySedeV();" data-live-search="true">
					                   <option value="">Selec. Sede</option>
					                   <?php echo $optSede; ?>
                                   </select>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <select id="selectNivelV" name="selectNivelV" class="form-group pickerButn" onchange="getGradosByNivelV();" data-live-search="true">
					                   <option value="">Selec. Nivel</option>
                                   </select>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <select id="selectGradoV" name="selectGradoV" class="form-group pickerButn" onchange="getAulasByGradoV();" data-live-search="true">
					                   <option value="">Selec. Grado</option>
                                   </select>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <select id="selectAulaV" name="selectAulaV" class="form-group pickerButn" data-live-search="true">
					                   <option value="">Selec. Aula</option>
                                   </select>
					           </div>
					       	   <div class="col-sm-12 p-r-20 p-l-5 mdl-input-group">
                             		<div class="mdl-icon mdl-icon__button">
                                        <button class="mdl-button mdl-js-button mdl-button--icon">				                            
                                            <i class="mdi mdi-today"></i>
			                            </button>
                                    </div>
	                       			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	                                    <input class="mdl-textfield__input" type="text" id="fecInicioV" name="fecInicioV" maxlength="10"/>
	                                    <label class="mdl-textfield__label" for="fecInicioV">Fecha Inicio</label>
	                                    <span class="mdl-textfield__error"></span>
	                                </div>                                       
                         	   </div>
                               <div class="col-sm-12 p-r-20 p-l-5 mdl-input-group">
                            		<div class="mdl-icon mdl-icon__button">
                                        <button class="mdl-button mdl-js-button mdl-button--icon">				                            
                                            <i class="mdi mdi-today"></i>
			                            </button>
                                    </div>
                                	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	                                    <input class="mdl-textfield__input" type="text" id="fecFinV" name="fecFinV" maxlength="10"/>
	                                    <label class="mdl-textfield__label" for="fecFinV">Fecha Fin</label>
	                                    <span class="mdl-textfield__error"></span>
	                                </div>
                               </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonPV" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-save__load accept" onclick="getAlumnosByAulaV();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        <div class="modal fade" id="modalFiltrarCuota" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content ">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtro</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-0 p-l-0">    				
					       <div class="row-fluid">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <select id="selectCronograma" name="selectCronograma" class="form-group pickerButn" onchange="getCuotaByCronograma();" data-live-search="true">
					                   <option value="">Selec. Cronograma</option>
					                   <?php echo $optCronograma; ?>
                                   </select>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <select id="selectCuota" name="selectCuota" class="form-group pickerButn" onchange="getNivelesByCuotas();" data-live-search="true">
					                   <option value="">Selec. Cuota</option>
                                   </select>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <select id=selectNivelC name="selectNivelC" class="form-group pickerButn" onchange="getGradosByNivelP();" data-live-search="true">
					                   <option value="">Selec. Nivel</option>
                                   </select>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <select id="selectGradoC" name="selectGradoC" class="form-group pickerButn" onchange="getAulasByGradoP();" data-live-search="true">
					                   <option value="">Selec. Grado</option>
                                   </select>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <select id="selectAulaC" name="selectAulaC" class="form-group pickerButn" onchange="getAlumnosByAulaP();" data-live-search="true">
					                   <option value="">Selec. Aula</option>
                                   </select>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonPP" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="getAlumnosByAulaP();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        <div class="modal fade" id="modalSelectFiltro" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content ">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtro</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-0 p-l-0">    				
					       <div class="row-fluid">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <select id="selectTableFiltro" name="selectTableFiltro" class="form-group pickerButn" data-live-search="true">
					                   <option value="">Selec. Filtro</option>
					                   <?php echo $optTablas;?>
                                   </select>
					           </div> 
					       </div>    				
					       <div class="row-fluid" id="comboBancos" style="display:none">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <select id="selectBancoFiltro" name="selectBancoFiltro" class="form-group pickerButn" data-live-search="true">
					                   <option value="">Selec. Banco</option>
					                   <?php echo $cmbBanco;?>
                                   </select>
					           </div> 
					       </div>
				       	   <div class="col-sm-12 p-r-20 m-l-5 mdl-input-group">
                                <div class="mdl-icon mdl-icon__button">
                                    <button class="mdl-button mdl-js-button mdl-button--icon">				                            
                                        <i class="mdi mdi-today"></i>
                                    </button>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="fecInicioAS" name="fecInicioAS" maxlength="10"/>
                                    <label class="mdl-textfield__label" for="fecInicioAS">Fecha Inicio</label>
                                    <span class="mdl-textfield__error"></span>
                                </div>                                       
                     	   </div>
                           <div class="col-sm-12 p-r-20 m-l-5 mdl-input-group">
                        		<div class="mdl-icon mdl-icon__button">
                                    <button class="mdl-button mdl-js-button mdl-button--icon">				                            
                                        <i class="mdi mdi-today"></i>
                                    </button>
                                </div>
                            	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="fecFinAS" name="fecFinAS" maxlength="10"/>
                                    <label class="mdl-textfield__label" for="fecFinAS">Fecha Fin</label>
                                    <span class="mdl-textfield__error"></span>
                                </div>
                           </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonAS" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="getTableByFiltro()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalFiltrarVerano" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content ">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtro</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-0 p-l-0">
					        <div class="row-fluid">
					            <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					                <select id="selectYearVerano" name="selectYearVerano" class="form-group pickerButn" data-live-search="true" onchange="habilitarComboSedes();">
					                    <option value="">Selec. Año</option>
					                    <?php echo $optYear;?>
                                    </select>
					            </div> 
					        </div>    				
					        <div class="row-fluid">
					            <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					                <select id="selectSedeVerano" name="selectSedeVerano" class="form-group pickerButn" data-live-search="true" disabled="disabled" onchange="getTiposBySede()">
					                    <option value="">Selec. Sede</option>
                                        <?php echo $optSede;?>
                                    </select>
					            </div> 
					        </div>
					        <div class="row-fluid">
					            <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					                <select id="selectTipoVerano" name="selectTipoVerano" class="form-group pickerButn" data-live-search="true" onchange="getTalleresByCronoTipo()">
					                    <option value="">Selec. Tipo</option>
					                    
                                    </select>
					            </div> 
					        </div>
					        <div class="row-fluid">
					            <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					                <select id="selectTallerVerano" name="selectTallerVerano" class="form-group pickerButn" data-live-search="true" onchange="getEstudiantesByTaller()">
					                    <option value="">Selec. Taller</option>
					                    
                                    </select>
					            </div> 
					        </div>
					    </div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonRV" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="getEstudiantesByTaller()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <form action="C_reporte_verano/downloadPDFByFiltroVerano" name="formPDFVerano" id="formPDFVerano" method="post">
            <input type="hidden" id="tallerVeranoPDF" name="tallerVeranoPDF">
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
   		<script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>jquery-mask/jquery.mask.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/js/jquery.treegrid.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/js/jquery.treegrid.bootstrap3.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts-more.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/exporting.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>hammer/velocity.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>hammer/hammer.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jshammer__reportes.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jsreportes.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jspagosPuntuales.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jspensionesPagadas.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jspensionesVencidas.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jsreporteVerano.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jsauditoria.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jscaja.js"></script>    
        <script type="text/javascript">  
            var optSedes = '<?php echo $optSede;?>';
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        	    $('.pickerButn').selectpicker('mobile');
        	} else {
        		$('.pickerButn').selectpicker();
        	}
            initButtonCalendarDays('fecInicioV');
			initButtonCalendarDays('fecFinV');
		    initMaskInputs('fecInicioV,fecFinV');
            initButtonCalendarDays('fecInicioPP');
			initButtonCalendarDays('fecFinPP');
		    initMaskInputs('fecInicioPP,fecFinPP');
            initButtonCalendarDays('fecInicioAS');
			initButtonCalendarDays('fecFinAS');
		    initMaskInputs('fecInicioAS,fecFinAS');
            var hoy = getFechaHoy_dd_mm_yyyy();
        	$("#fecInicioPP").val(hoy);
        	$("#fecFinPP").val(hoy);
        	$("#fecInicioV").val(hoy);
        	$("#fecFinV").val(hoy);
        	$("#fecInicioAS").val(hoy);
        	$("#fecFinAS").val(hoy);
			$('.tree').treegrid({
				initialState: 'collapsed',
                expanderExpandedClass: 'mdi mdi-keyboard_arrow_up',
                expanderCollapsedClass: 'mdi mdi-keyboard_arrow_down'
            });  
			initButtonLoad('botonAS','botonPP','botonPV','botonRV');
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip(); 
            });
        </script>
	</body>
</html>