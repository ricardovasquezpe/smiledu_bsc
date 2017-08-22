<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Gr&aacute;ficos | <?php echo NAME_MODULO_BSC?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_BSC?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_BSC?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS;?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>floating-button/mfb.css">
    	<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS?>multi_select/bootstrap-multiselect.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>toaster/toastr.css">
    	<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PUBLIC_BSC?>css/shideEffect.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC;?>css/submenu.css">
        <style>
            
        @media (min-width: 769px) {
          
          .contenido-indica{
                align-self: center;
            }
            	
            .indica{	
                display:flex;
            }
        } 
        
        section {
            padding: 15px 0 !important;
            margin: 0;
            width: 100%;
            max-width: 773px;
            margin: auto;
        	text-align: center;
        }
        
        @media ( max-width : 750px ) {
        	section {
        	    padding: 15px !important	
        	}           	
        }  
                
         </style>
         
	</head>
	
	<body>   
	   <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' > 
    		<?php echo $menu ?>           
            <main class='mdl-layout__content'>  
                 <section>
                    <div class="mdl-card" style="display: none; z-index:2;" id="contYear">
                            <div class="mdl-card__supporting-text br-b br-t" id="contTabCompe">
    					        <div class="form-group">
                                    <select id="selectYear" name="selectYear" data-live-search="true" class="form-control" onchange="getGraficoBySedesYear()">
                                        <option value="2014">2014</option>
                                        <option value="2015">2015</option>
                                        <option value="2016">2016</option>
                                        <option value="2017">2017</option>
                                        <option value="2018">2018</option>
                                    </select>
                                </div>
    						</div>    
                            <div class="img-search" id="cont_search_not_filter" style="display: none;">
                                <img src="<?php echo RUTA_IMG?>smiledu_faces/not_filter_fab.png">
                                <p><strong>&#161;Ups!</strong></p>
                                <p>Tu filtro no ha sido</p>
                                <p>encontrado.</p>
                            </div>
                        </div>
                   <div class="mdl-card">
                    <div class="mdl-content-cards" >
                        <div id="container"></div>
                    </div>
                  
                    <div class="img-search" id="cont_not_found_fab">
                        <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                        <p>Primero debemos filtrar para</p>
                        <p>visualizar el gr&aacute;fico.</p>
                    </div>
                  </div>
                </section>
             </main>         
        </div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
        		<button class="mfb-component__button--main" data-mfb-label="Filtrar" onclick="abrirModalFitros()">
        			<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
        			<i class="mfb-component__main-icon--active mdi mdi-filter_list"></i>
        		</button>
        	</li>
        </ul>
    	
        <div class="offcanvas"></div> 
           
        <div class="modal fade" id="modalFiltroGrafico" tabindex="-1" aria-labelledby="simpleModalLabel" aria-hidden="true">
	        <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar</h2>
    					</div>
    					<div class="mdl-card__supporting-text p-0">
					       <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect"> 
    					      <div class="mdl-tabs__tab-bar m-b-10">
                                  <a href="#filtroCombos" class="mdl-tabs__tab is-active">Plan Estrat&eacute;gico</a>
                                  <a href="#filtroIndicador" class="mdl-tabs__tab">Indicador</a>
                              </div>
                               <div class="mdl-tabs__panel is-active" id="filtroCombos">			
        					       <div class="row-fluid">
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
        					               <div class="mdl-select">    
            					               <select id="selectLinea" name="selectLinea"  data-live-search="true" class="form-control pickerButn" onchange="getObjetivosByLinea()">
                                                    <option>Seleccione L&iacute;nea Estrat&eacute;gica</option>
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
                                                   <option>Seleccione Categor&iacute;a</option>
                                               </select>
                                           </div>
                                       </div>
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                           <div class="mdl-select"> 
                                               <select id="selectIndicador" name="selectIndicador"  data-live-search="true" class="form-control pickerButn" onchange="getGraficoByIndicador()">
                                                   <option>Seleccione Indicador</option>
                                               </select>
                                           </div>
                                       </div>
        					           <br/>
                                       <div class="form floating-label">
                                           <div id="contCombosFiltro"></div>
                                       </div>
        					       </div>
    					       </div>
    					       <div class="mdl-tabs__panel" id="filtroIndicador">			
        					       <div class="row-fluid">
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
            				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="div_codigo">
                                                <input class="mdl-textfield__input" type="text" id="codigo" name="codigo"
                                                       onkeypress="buscarIndiByCod(event);" onchange="buscarIndiByCod();">        
                                                <label class="mdl-textfield__label" for="codigo">C&oacute;digo o Nombre</label>                            
                                            </div>
        				               </div>
        				               <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                           <div class="mdl-select"> 
                                               <select id="selectIndi" name="selectIndi" data-live-search="true" class="form-control pickerButn" onchange="getGraficoByIndicador('cod')">
                                                    <option> Selec. Indicador</option>
                                               </select>
                                           </div>
                                       </div>
        					           <br/>
                                       <div class="form floating-label">
                                           <div id="contCombosFiltroCod"></div>
                                       </div> 
        					       </div>
    					       </div>
					       </div>
    					</div>
        				<div class="mdl-card__actions">
        				    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
        				    <button id="botonFI" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal">Aceptar</button>
    				    </div>
    				</div>
	            </div>
    		</div>
    	</div>
        
        <script src="<?php echo RUTA_JS;?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS;?>bootstrap-validator/bootstrapValidator.min.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_PLUGINS;?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>mdl/js/material.min.js"></script> 
        <script src="<?php echo RUTA_PLUGINS;?>highcharts/js/highcharts.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_PLUGINS;?>highcharts/js/highcharts-more.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_PLUGINS;?>highcharts/js/modules/exporting.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script>  
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>multi_select/bootstrap-multiselect.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC;?>js/jsutilsbsc.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC;?>js/jsgrafico.js"></script>
	
	   <script>
        	init();
        	$('main.mdl-layout__content').addClass('is-visible');
        	$('#selectYear').val(new Date().getFullYear());
            $('#selectYear').selectpicker('render');
            $('#selectYear').selectpicker('refresh');
    	</script>
	</body>
</html>