<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Gr&aacute;ficos | <?php echo NAME_MODULO_SPED;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SPED;?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_SPED;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>graficos.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SPED?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SPED?>css/graficos_desempeno.css"/>
	</head>
    <style>
        .chart_new {
            width:100%;
            min-height:400px;
        	margin: auto;
        }
        
        svg:first-child > g > text[text-anchor~=middle]{
            font-size:12px;
        }
	</style>
	<body>
    	
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
        	<?php echo $menu?>

        	<main class='mdl-layout__content is-visible' id="base">
        	    <!-- BEGIN DOCENTES -->
                <section class="mdl-layout__tab-panel is-active" id="tab-1">
                    <div class="page-content">
                        <div class="row-fluid">
                            <div class="col-md-10 col-md-offset-1 p-0">
                                <div class="col-sm-6" id="cDoc">	
                				    <div class="mdl-card m-b-10">
                				        <div class="mdl-card__title">
                                            <h2 class="mdl-card__title-text">Indicadores</h2>
                                        </div>
                                        <div class="mdl-card__supporting-text br-b">
                                            <div id="containerDoc" class="chart_new"></div>
                                        </div>
                                        <div class="mdl-card__menu">
                                            <a id="aDownloadDoc1" download="filename.jpg" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"><i class="mdi mdi-file_download"></i></a>
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="refreshGraf(4)"><i class="mdi mdi-autorenew"></i></button>
                                        </div>
                					</div>
                				</div>
        				             				
                				<div class="col-sm-6" id="c2Doc">	
                				    <div class="mdl-card m-b-10">
                				        <div class="mdl-card__title">
                                            <h2 class="mdl-card__title-text">Subfactores a mejorar</h2>
                                        </div>
                                        <div class="mdl-card__supporting-text br-b">
                                            <div id="containerDoc2" class="chart_new"></div>
                                        </div>
                                        <div class="mdl-card__menu">
                                            <a id="aDownloadDoc2" download="filename.jpg" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"><i class="mdi mdi-file_download"></i></a>
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="refreshGraf(5)"><i class="mdi mdi-autorenew"></i></button>
                                        </div>
                					</div>
                				</div>
                				
                				<div class="col-sm-6" id="c3Doc">	
                				    <div class="mdl-card m-b-10">
                				        <div class="mdl-card__title">
                                            <h2 class="mdl-card__title-text">Docente por Indicadores</h2>
                                        </div>
                                        <div class="mdl-card__supporting-text br-b">
                                            <div id="containerDoc3" class="chart_new"></div>
                                            <div id="contCanvas">
                                                <canvas id="canvas" style="display: none;">Sorry, no canvas available</canvas>
                                            </div>
                                        </div>
                                        <div class="mdl-card__menu">
                                            <a id="aDownloadDoc3" download="filename.jpg" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"><i class="mdi mdi-file_download"></i></a>
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="refreshGraf(6)"><i class="mdi mdi-autorenew"></i></button>
                                        </div>
                					</div>
                				</div>
                				               				
                				<div class="col-sm-6" id="c4Doc">	
                				    <div class="mdl-card m-b-10">
                				        <div class="mdl-card__title">
                                            <h2 class="mdl-card__title-text">Evaluaciones por &Aacute;rea</h2>
                                        </div>
                                        <div class="mdl-card__supporting-text br-b">
                                            <div id="containerDoc4" class="chart_new"></div>
                                        </div>
                                        <div class="mdl-card__menu">
                                            <a id="aDownloadDoc4" download="filename.jpg" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"><i class="mdi mdi-file_download"></i></a>
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="refreshGraf(7)"><i class="mdi mdi-autorenew"></i></button>
                                        </div>
                					</div>
                				</div>
                				
                				<div class="col-sm-6" id="c5Doc">	
                				    <div class="mdl-card m-b-10">
                				        <div class="mdl-card__title">
                                            <h2 class="mdl-card__title-text">Top de docentes</h2>
                                        </div>
                                        <div class="mdl-card__supporting-text br-b">
                                            <div id="container5Doc" class="chart_new"></div>
                                        </div>
                                        <div class="mdl-card__menu">
                                            <a id="aDownloadDoc5" download="filename.jpg" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"><i class="mdi mdi-file_download"></i></a>
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="refreshGraf(8)"><i class="mdi mdi-autorenew"></i></button>
                                        </div>
                					</div>
                				</div>
                				
                				<div class="col-sm-6" id="c6Doc">	
                				    <div class="mdl-card m-b-10">
                				        <div class="mdl-card__title">
                                            <h2 class="mdl-card__title-text">Docentes por mejorar</h2>
                                        </div>
                                        <div class="mdl-card__supporting-text br-b">
                                            <div id="container6Doc" class="chart_new"></div>
                                        </div>
                                        <div class="mdl-card__menu">
                                            <a id="aDownloadDoc6" download="filename.jpg" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"><i class="mdi mdi-file_download"></i></a>
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="refreshGraf(9)"><i class="mdi mdi-autorenew"></i></button>
                                        </div>
                					</div>
                				</div>           				
            				</div>                				
                        </div>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel" id="tab-2">
                    <div class="page-content">
                        <div class="row-fluid">
                            <div class="col-sm-5 col-sm-offset-1 p-0">
                                <div class="col-sm-12 p-0" id="c1">	
                				    <div class="mdl-card m-b-10">
                				        <div class="mdl-card__title">
                                            <h2 class="mdl-card__title-text">Evaluadores por tipo de visita</h2>
                                        </div>
                                        <div class="mdl-card__supporting-text br-b">
                                            <div id="container1EvaTipoVisita" class="chart_new"></div>
                                        </div>
                                        <div class="mdl-card__menu">
                                            <a id="aDownload1" download="filename.jpg">Bajar</a>
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="refreshGraf(3)"><i class="mdi mdi-autorenew"></i></button>
                                        </div>
                					</div>
                				</div>
                				
                				<div class="col-sm-12 p-0" id="c3">	
                				    <div class="mdl-card m-b-10">
                				        <div class="mdl-card__title">
                                            <h2 class="mdl-card__title-text">Evaluadores por estado</h2>
                                        </div>
                                        <div class="mdl-card__supporting-text br-b">
                                            <div id="container3Eva" class="chart_new"></div>
                                        </div>
                                        <div class="mdl-card__menu">
                                            <a id="aDownload3" download="filename.jpg">Bajar</a>
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="refreshGraf(3)"><i class="mdi mdi-autorenew"></i></button>
                                        </div>
                					</div>
                				</div>
            				</div>

            				<div class="col-sm-5 p-0">
                				<div class="col-sm-12 p-0" id="c2">	
                				    <div class="mdl-card m-b-10">
                				        <div class="mdl-card__title">
                                            <h2 class="mdl-card__title-text">Evaluaciones en el tiempo</h2>
                                        </div>
                                        <div class="mdl-card__supporting-text br-b">
                                            <div id="container2Eva" class="chart_new"></div>
                                        </div>
                                        <div class="mdl-card__menu">
                                            <a id="aDownload2" download="filename.jpg">Bajar</a>
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="refreshGraf(2)"><i class="mdi mdi-autorenew"></i></button>
                                        </div>
                					</div>
                				</div>                				
            				</div>
            				<div class="col-sm-5 p-0">
                				<div class="col-sm-12 p-0" id="c2">	
                				    <div class="mdl-card m-b-10">
                				        <div class="mdl-card__title">
                                            <h2 class="mdl-card__title-text">Evaluadores</h2>
                                        </div>
                                        <div class="mdl-card__supporting-text br-b">
                                            <div id="container4Eva" class="chart_new"></div>
                                        </div>
                                        <div class="mdl-card__menu">
                                            <a id="aDownload" download="filename.jpg">Bajar</a>
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="refreshGraf(1)"><i class="mdi mdi-autorenew"></i></button>
                                        </div>
                					</div>
                				</div>                				
            				</div>
                        </div>
                    </div>
                </section>
                <!-- END EVALUADORES -->
            </main>
    	</div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap">
                <button class="mfb-component__button--main" >
                    <i class="mfb-component__main-icon--resting mdi mdi-add"></i>
                </button>
                <button class="mfb-component__button--main" data-mfb-label="Filtrar" id="optionFiltro">
                    <i class="mfb-component__main-icon--active mdi mdi-filter_list"></i>
                </button>
                <ul class="mfb-component__list">
                   <li>
                       <button class="mfb-component__button--child" data-mfb-label="Gr&aacute;ficos" onclick="abrirCerrarModal('modalPaneles');" >
                           <i class="mfb-component__child-icon mdi mdi-check_box"></i>
                       </button>
                   </li>                      
                </ul>
            </li>
        </ul>
                				
		<div class="modal fade" id="modalDetaEvaluadorDocente" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
        	<div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                       <div class="mdl-card__title">
    					  <h2 class="mdl-card__title-text" id="titleTableDetaEvaluadorDocente">Detalle de fecha de evaluaci&oacute;n a docente</h2>
    				   </div>
					   <div class="mdl-card__supporting-text p-b-0">
					       <div id="cont_tableEvaluadorDocente"></div>
					   </div>
			       </div>
                </div>
            </div>
        </div>
    	
        <div class="modal fade" id="modalDocentes" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Subfactores vs. docentes</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-b-0">
					       <div class="row-fluid">
					           <div class="col-xs-6 mdl-modal_row">
					               <select id="selectIndi" name="selectIndi" multiple="multiple" class="form-control pickerButn" data-size="30" data-live-search="true" 
                                           data-selected-text-format="count > 2">
                                        <?php echo $optIndicadores;?>
                                   </select>
					           </div>
					           <div class="col-xs-6 mdl-modal_row">
					               <select id="selectDocente" name="selectDocente" multiple="multiple" class="form-control pickerButn" data-size="10" data-live-search="true" 
                                        data-selected-text-format="count > 3">
                                        <?php echo $optDocentes;?>
                                   </select>
					           </div>
					           <div class="col-xs-6 mdl-modal_row">
					               <div class="mdl-textfield mdl-js-textfield">
					                   <input class="mdl-textfield__input" type="text" name="fecInicioDoc" id="fecInicioDoc" value="<?php echo date('d/m/Y', strtotime('-7 days'));?>">
					                   <label class="mdl-textfield__label" for="fecInicioDoc">Fecha Inicio</label>
					               </div>
					           </div>
					           <div class="col-xs-6 mdl-modal_row">
					               <div class="mdl-textfield mdl-js-textfield">
					                   <input class="mdl-textfield__input" type="text" name="fecFinDoc" id="fecFinDoc" value="<?php echo date('d/m/Y');?>">
					                   <label class="mdl-textfield__label" for="fecFinDoc">Fecha Inicio</label>
					               </div>
					           </div>
					           <div class="col-xs-12 mdl-modal_row">
					               <div id="container_versus" class="chart_new"></div>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions p-t-0">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="getGraficoVersus();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        <div class="modal fade" id="modalSubfactoresLow" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="titleTableSubFLow">Det. Subfactores Low</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-b-0">
					       <div class="row-fluid">
					           <div class="col-xs-12 mdl-modal_row">
					               <div id="container_Deta_Subf_Low" class="chart_new"></div>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions p-t-0">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="getGraficoSubFactLow();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalSubfactores" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="titleTableSubF">Detalle Subfactor: </h2>
    					</div>
					    <div class="mdl-card__supporting-text p-b-0">
					       <div class="row-fluid">
					           <div class="col-xs-12 mdl-modal_row">
					               <div id="container_Deta_Subf" class="chart_new"></div>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions p-t-0">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="getGraficoSubFact();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        <div class="modal fade" id="modalEvaluadores" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Buscar estad&iacute;stica</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-b-0">
					       <div class="row-fluid">
					           <div class="col-xs-12 mdl-modal_row">
					               <select id="selectEvaluador" name="selectEvaluador" multiple="multiple" class="form-group pickerButn" data-size="30"
                                           onchange="onchangeRoles();" data-live-search="true" data-selected-text-format="count > 3">
                                       <?php echo $optEvaluadores?>
                                   </select>
					           </div>
					           <div class="col-xs-12 mdl-modal_row">
					               <div class="mdl-textfield mdl-js-textfield">
					                   <input class="mdl-textfield__input" type="text" onchange="onchangeRoles();" name="fecInicioEva" id="fecInicioEva" value="<?php echo date('d/m/Y');?>">
					                   <label class="mdl-textfield__label" for="fecInicioEva">Fecha Inicio</label>
					               </div>
					           </div>
					           <div class="col-xs-12 mdl-modal_row">
					               <div class="mdl-textfield mdl-js-textfield">
					                   <input class="mdl-textfield__input" type="text" onchange="onchangeRoles();" name="fecFinEva" id="fecFinEva" value="<?php echo date('d/m/Y');?>">
					                   <label class="mdl-textfield__label" for="fecFinEva">Fecha Inicio</label>
					               </div>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions p-t-0">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalPaneles" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card m-b-10">
    			        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Gr&aacute;ficos</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="indicador">
                                <input type="checkbox" id="indicador" class="mdl-checkbox__input" checked onchange="showHidePanel(this);">
                                <span class="mdl-checkbox__label">Indicadores</span>
                            </label>
                            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="porcIndi">
                                <input type="checkbox" id="porcIndi" class="mdl-checkbox__input" checked onchange="showHidePanel(this);">
                                <span class="mdl-checkbox__label">Porcentaje de Indicadores</span>
                            </label>
                            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="areas">
                                <input type="checkbox" id="areas" class="mdl-checkbox__input" checked onchange="showHidePanel(this);">
                                <span class="mdl-checkbox__label">Docente Por Indicador</span>
                            </label>
                            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="doc">
                                <input type="checkbox" id="doc" class="mdl-checkbox__input" checked onchange="showHidePanel(this);">
                                <span class="mdl-checkbox__label">Docentes</span>
                            </label>
                            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="prom">
                                <input type="checkbox" id="prom" class="mdl-checkbox__input" checked onchange="showHidePanel(this);">
                                <span class="mdl-checkbox__label">Promedio Anual de Indicadores</span>
                            </label>
                            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="promd">
                                <input type="checkbox" id="promd" class="mdl-checkbox__input" checked onchange="showHidePanel(this);">
                                <span class="mdl-checkbox__label">Promedio Anual de Docente</span>
                            </label>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Cerrar</button>
                        </div>
    				</div>
                </div>
            </div>
		</div>
		
		<div class="modal fade" id="modalDetalleEvaluadores" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
        	<div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                       <div class="mdl-card__title">
    					  <h2 class="mdl-card__title-text" id="titleTable">Detalle de Evaluador</h2>
    				   </div>
					   <div class="mdl-card__supporting-text p-0 br-b" id="body"></div>
				       <div class="mdl-card__menu">
				          <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				               <i class="mdi mdi-close"></i>
			              </button>
				       </div>
			       </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalDetalleEvaluacionesEvaluadores" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
        	<div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card">
                       <div class="mdl-card__title">
    					  <h2 class="mdl-card__title-text" id="titleTableDetalle"></h2>
    				   </div>
					   <div class="mdl-card__supporting-text p-0 br-b" id="bodyDetalle">    				
				       </div>
				       <div class="mdl-card__menu">
				          <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				               <i class="mdi mdi-close"></i>
			              </button>
				       </div>
			       </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-lg" id="modalTamano">
                <div class="modal-content">
                    <div class="mdl-card__title">
        			    <h2 class="mdl-card__title-text" id="titleTableDetalle2">Detalle de Docente</h2>
        		    </div>
        		    <div class="mdl-card__supporting-text p-0 br-b"  id="contTableDetalle"></div>
        			<div class="mdl-card__menu">
        	            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
        	               <i class="mdi mdi-close"></i>
                        </button>
        	        </div>
                </div>
            </div>
    	</div>
    	
    	<div class="modal fade" id="modalDetaDoceArea" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
        	<div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                       <div class="mdl-card__title">
    					  <h2 class="mdl-card__title-text" id="titleTableDetaDoceArea">Detalle de Evaluaciones por docente</h2>
    				   </div>
					   <div class="mdl-card__supporting-text p-b-0">
					       <div id="containerDoc4_deta" class="chart_new"></div>
					   </div>
				       <div class="mdl-card__menu">
				           <a id="aDownloadDetaDoc4" download="filename.jpg">Bajar</a>
                           <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="refreshGraf(1)"><i class="mdi mdi-autorenew"></i></button>
				           <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				               <i class="mdi mdi-close"></i>
			               </button>
				       </div>
			       </div>
                </div>
            </div>
        </div>
        
          <div class="modal fade" id="modalDetaEvalDocentes1" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                 <div class="modal-content">
                       <div class="mdl-card">
                           <div class="mdl-card__title">
                            	<h2 class="mdl-card__title-text" id="titleTableDetaTopDoc">Detalle evaluación a docente</h2>
                           </div>
                    	   <div class="mdl-card__supporting-text p-0 br-b">
                        	   <div class="bootstrap-table">
                        		  <div class="table-responsive">
                        			  <div id="cont_tableEvalDocentes1"></div>
                        		   </div>
                        		</div>
                        		<div class="mdl-card__actions text-right">
					               <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
					            </div>
                    		</div>
                        </div>
                   </div>
              </div>
           </div>
        
        <div class="modal fade" id="modalDetaSubfLow" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
        	<div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                       <div class="mdl-card__title">
    					  <h2 class="mdl-card__title-text" id="titleTableDetaSubfLow">Detalle de Subfactores por mejorar</h2>
    				   </div>
					   <div class="mdl-card__supporting-text p-b-0">
					       <div id="containerDoc5_deta" class="chart_new"></div>
					   </div>
				       <div class="mdl-card__menu">
				           <a id="aDownloadDetaDoc5" download="filename.jpg">Bajar</a>
                           <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="refreshGraf(1)"><i class="mdi mdi-autorenew"></i></button>
				           <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				               <i class="mdi mdi-close"></i>
			               </button>
				       </div>
			       </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalDetaEvalDocentes2" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                 <div class="modal-content">
                       <div class="mdl-card">
                           <div class="mdl-card__title">
                            	<h2 class="mdl-card__title-text" id="titleTableDetaDocxMejorar">Detalle Evaluación a docentes por mejorar</h2>
                            	</div>
                        		<div class="mdl-card__supporting-text p-b-0">
                        			<div id="cont_tableEvalDocentes2"></div>
                        		</div>
                        		<div class="mdl-card__actions text-right">
					               <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
					            </div>
                        		</div>
                            </div>
                     </div>
              </div>
              
         <div class="modal fade" id="modalDetaEvalTipoVisita" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                 <div class="modal-content">
                       <div class="mdl-card">
                           <div class="mdl-card__title">
                            	<h2 class="mdl-card__title-text" id="titleTableDetaTipoVisita">Detalle evaluación por tipo de visita</h2>
                           </div>
                    	   <div class="mdl-card__supporting-text p-0 br-b">
                        	   <div class="bootstrap-table">
                        		  <div class="table-responsive">
                        			  <div id="cont_tableEvalTipoVisita"></div>
                        		   </div>
                        		</div>
                        		<div class="mdl-card__actions text-right">
					               <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
					            </div>
                    		</div>
                        </div>
                   </div>
              </div>
           </div>
           
           <div class="modal fade" id="modalDetaEvalHechasXHacer" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                 <div class="modal-content">
                       <div class="mdl-card">
                           <div class="mdl-card__title">
                            	<h2 class="mdl-card__title-text" id="titleTableDetaHechasXHacer">Detalle evaluaciones hechas y por hacer</h2>
                           </div>
                    	   <div class="mdl-card__supporting-text p-0 br-b">
                        	   <div class="bootstrap-table">
                        		  <div class="table-responsive">
                        			  <div id="cont_tableEvalHechasXHacer"></div>
                        		   </div>
                        		</div>
                        		<div class="mdl-card__actions text-right">
					               <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
					            </div>
                    		</div>
                        </div>
                   </div>
              </div>
           </div>
        
        <div class="modal fade" id="modalDetaEvaArea" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
        	<div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                       <div class="mdl-card__title">
    					  <h2 class="mdl-card__title-text" id="titleTableDetaEvaArea">Detalle de evaluaciones por área</h2>
    				   </div>
					   <div class="mdl-card__supporting-text p-b-0">
					       <div id="cont_tableDetaEvaArea"></div>
					   </div>
					   <div class="mdl-card__actions text-right">
    		               <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
    		            </div>
			       </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalDetaDocFechaLow" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
        	<div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card">
                       <div class="mdl-card__title">
    					  <h2 class="mdl-card__title-text titulo3">Detalle de fecha de evaluación a docente</h2>
    				   </div>
					   <div class="mdl-card__supporting-text p-rl-0">
					       <div id="cont_tableEvaDocente"></div>
					   </div>
					   <div class="mdl-card__actions">
					       <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
					   </div>
			       </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalDetaSubf" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
        	<div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                       <div class="mdl-card__title">
    					  <h2 class="mdl-card__title-text" id="titleTableDetaSubf">Detalle de Subfactores</h2>
    				   </div>
					   <div class="mdl-card__supporting-text p-b-0">
					       <div id="containerDoc11_deta" class="chart_new"></div>
					   </div>
				       <div class="mdl-card__menu">
				           <a id="aDownloadDetaDoc11" download="filename.jpg">Bajar</a>
                           <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="refreshGraf(1)"><i class="mdi mdi-autorenew"></i></button>
				           <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				               <i class="mdi mdi-close"></i>
			               </button>
				       </div>
			       </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalDetaDocFechaAscendente" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
        	<div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card">
                       <div class="mdl-card__title">
    					  <h2 class="mdl-card__title-text titulo4">Detalle de fecha de evaluación a docente</h2>
    				   </div>
					   <div class="mdl-card__supporting-text p-rl-0">
					       <div id="cont_tableEvaDocenteAscendente"></div>
					   </div>
					   <div class="mdl-card__actions">
					       <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
					   </div>
			       </div>
                </div>
            </div>
        </div>

        <div id="divFechas"></div>

        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
   		
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
	    <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts-more.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/exporting.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/export-csv.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>google_chart/loader.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>html2canvas/html2canvas.js"></script>
                
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_SPED;?>js/jsgrafico.js"></script>
        <script src="<?php echo RUTA_PUBLIC_SPED;?>js/jsdesempenoEvaluadores.js"></script>
        
        <script type="text/javascript">
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
            	$('#selectIndi').selectpicker('mobile');
                $('#selectDocente').selectpicker('mobile');
                $('#selectEvaluador').selectpicker('mobile');
                $('#selectRoles').selectpicker('mobile');
            } else {
            	$('#selectIndi').selectpicker({noneSelectedText: 'Seleccione subfactores'});
                $('#selectDocente').selectpicker({noneSelectedText: 'Seleccione docentes'});
                $('#selectEvaluador').selectpicker({noneSelectedText: 'Seleccione un Evaluador'});
                $('#selectRoles').selectpicker({noneSelectedText: 'Seleccione un Rol'});
            }

        	$('#fecInicioDoc, #fecFinDoc, #fecInicioEva, #fecFinEva').bootstrapMaterialDatePicker({ weekStart : 0, time: false, format : 'DD/MM/YYYY' });
			
        	changeIdModal('modalDocentes');

        	var jsonDataGrafDoc1 = '<?php echo $doce1Graf;?>';
        	var jsonDataGrafDoc2 = '<?php echo $doce2Graf;?>';
        	var jsonDataGrafDoc3 = '<?php echo $doce3Graf;?>';
        	var jsonDataGrafDoc4 = '<?php echo $doce4Graf;?>';
        	var jsonDataGrafDoc5 = '<?php echo $doce5Graf;?>';
        	var jsonDataGrafDoc6 = '<?php echo $doce6Graf;?>';
        	var jsonDataGraf1    = null;
        	var jsonDataGraf2    = null;
        	var jsonDataGraf3    = null;
        	google.charts.load('current', {'packages':['corechart', 'line', 'bar', 'gauge']});
        	google.charts.setOnLoadCallback(drawChartDoc1);
        	google.charts.setOnLoadCallback(drawChartDoc2);
        	google.charts.setOnLoadCallback(drawChartDoc3);
        	google.charts.setOnLoadCallback(drawChartDoc4);
        	google.charts.setOnLoadCallback(drawChartDoc5);
        	google.charts.setOnLoadCallback(drawChartDoc6);
        	
        	$(window).resize(function() {
        		drawChartDoc1();
        		drawChartDoc2();
        		drawChartDoc3();
        		drawChartDoc4();
        		drawChartDoc5();
        		drawChart();
        		drawChart2();
        		drawChart3();
      		});
		</script>
	</body>
</html>