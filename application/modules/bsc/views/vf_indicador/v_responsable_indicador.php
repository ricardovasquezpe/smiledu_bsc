<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
         <script type="text/javascript">
             var timeInit = performance.now();             
        </script>       
        <title>Responsables | <?php echo NAME_MODULO_BSC?></title>
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
    	<!--  link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/-->
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC?>css/mdl-card-style.css">
        
	</head>

	<body onload="screenLoader(timeInit);">
	   <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
	       <?php echo $menu ?>
           <main class='mdl-layout__content'>  
                <section>
                    <div class="mdl-content-cards">
                        <div class="col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
                            <div class="mdl-card " id="infoBasica">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text" id="titleResponsables">Responsables</h2>
                                </div>
                                <div class="mdl-card__supporting-text p-0 br-b">                                    
                                    <div id="contTbPersonas" class="form floating-label table_distance">
        						        <?php echo $tablePersonaIndicador?>
        					        </div>
                                </div> 
                                
                                <div class="mdl-card__menu">
                                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="abirModalAisgnarPersonas();" id="btnAddResponsableIndicador" style="display:none">
                                        <i class="mdi mdi-edit"></i>
                                    </button>
                                </div>                               
                            </div>
                        </div>   
                    </div> 
                </section>
            </main>
        </div>
              
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
    		<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
        		<button  class="mfb-component__button--main" id="main_button" onclick="abrirCerrarModal('modalFiltro')"" data-mfb-label="Filtrar"> 
        			<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
    		    </button>
		    </li>
    	</ul>
    	
        <div class="modal fade" id="modalFiltro" tabindex="-1" aria-labelledby="simpleModalLabel" aria-hidden="true">
	        <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar</h2>
    					</div>
    					<div class="mdl-card__supporting-text p-0">
					       <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect"> 
    					      <div class="mdl-tabs__tab-bar">
                                  <a href="#filtroCombos" class="mdl-tabs__tab is-active">Plan Estrat&eacute;gico</a>
                                  <a href="#filtroIndicador" class="mdl-tabs__tab">Indicador</a>
                              </div>
                               <div class="mdl-tabs__panel is-active" id="filtroCombos">			
        					       <div class="row-fluid p-t-15">
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
            					           <div class="mdl-select">           
            					               <select id="selectLinea" name="selectLinea"  data-live-search="true" class="form-control pickerButn" onchange="getObjetivosByLinea()">
                                                    <option>Seleccione Línea Estratégica</option>
                                                    <?php echo $lineaEstrat;?>
                                                </select>
                                            </div>
        					           </div>
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                           <div class="mdl-select"> 
                                               <select id="selectObjetivo" name="selectObjetivo"  data-live-search="true" class="form-control pickerButn" onchange="getCategoriaByObjetivo()">
                                                   <option>Seleccione Objetivo</option>
                                               </select>
                                           </div>
                                       </div>
                                       <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                           <div class="mdl-select"> 
                                               <select id="selectCategoria" name="selectCategoria"  data-live-search="true" class="form-control pickerButn" onchange="getIndicadoresByCategoria()">
                                                   <option>Seleccione Categoría</option>
                                               </select>
                                           </div>
                                       </div>
                                       <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                           <div class="mdl-select"> 
                                               <select id="selectIndicador" name="selectIndicador"  data-live-search="true" class="form-control pickerButn" onchange="getPersonasByIndicador()">
                                                   <option>Seleccione Indicador</option>
                                               </select>
                                           </div>
                                       </div>
        					       </div>
    					       </div>
    					       <div class="mdl-tabs__panel" id="filtroIndicador">			
        					       <div class="row p-0 m-0">
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
            				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="codNombreFiltroIndicador" name="codNombreFiltroIndicador" onchange="filtrarPorIndicador()">        
                                                <label class="mdl-textfield__label" for="codNombreFiltroIndicador">Nombre o COD Indicador</label>                            
                                            </div>
        				               </div>
        				               <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                           <div class="mdl-select"> 
                                               <select id="selectIndicadorCod" name="selectIndicadorCod"  data-live-search="true" class="form-control pickerButn" onchange="getPersonasByIndicadorCod()">
                                                   <option>Seleccione Indicador</option>
                                               </select>
                                           </div>
                                       </div>
        					       </div>
    					       </div>
					       </div>
    					</div>
        				<div class="mdl-card__actions p-t-20">
        				    <button  id="botonF" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal">Aceptar</button>
    				    </div>
    				</div>
	            </div>
    		</div>
    	</div>
    	
    	<div class="modal fade" id="modalAsignaPersonas" tabindex="-1" aria-labelledby="simpleModalLabel" aria-hidden="true">
	        <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Asignar Responsable</h2>
    					</div> 
    					<div class="mdl-card__supporting-text p-rl-0 p-t-0">
    					   <input type="hidden" id="idIndDeta">
    					   <div class="row-fluid">
                                <div class="col-sm-12 p-r-15 p-l-15">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" name="nombrePersona" id="nombrePersona" onchange="getPersonasAddIndicador();">
                                        <label class="mdl-textfield__label" for="nombrePersona">Nombres</label>
                                    </div>
    						    </div>
                                <div class="col-sm-12 p-0">
                                  <div id="contTbPersonasModal"></div>
    						    </div>
    					   </div>
    					</div>
        				<div class="mdl-card__actions">
        				    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
        				    <button id="btnCIP" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="capturarIndicadoresPersona();">Asignar</button>
    				    </div>
    				</div>
	            </div>
    		</div>
    	</div>
    	
    	<div class="modal fade" id="modalEliminarResponsable" tabindex="-1" aria-labelledby="simpleModalLabel" aria-hidden="true">
	        <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Desea quitar al responsable?</h2>
    					</div> 
    					<div class="mdl-card__supporting-text m-b-10">
    					   <small>Al eliminar al responsable se le quitara los permisos que tiene en dicho indicador.</small>
    					</div>
        				<div class="mdl-card__actions">
        				    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
        				    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="save-buttom" type="submit" onclick="eliminarResponsableByIndicador();">Aceptar</button>
    				    </div>
    				</div>
	            </div>
    		</div>
    	</div>
            
		<form action="c_main/logout" name="logout" id="logout" method="post"></form>

		<script src="<?php echo RUTA_JS;?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script> 
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>  
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC?>js/jsutilsbsc.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC?>js/jsresponsable_indicador.js"></script>

        <script type="text/javascript">
        initResponsableIndicador();
        </script>
	
	
	</body>
</html>